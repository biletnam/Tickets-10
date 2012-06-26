<?php

class CRuntime {

    public $db;  // database 
    public $txt; // text processor
    public $v;   // validator
    private $timestart;
    public $timegauge = array();
    public $config = array();
    private $attempt = 1;
    private $dictionarycache = array();
    
    // variables to hold current action context
    public $module;
    public $action;

    public function __construct($config = array()) {
        $this->timestart = $this->microtime_float();
        $this->config = $config;
    }
    
    public function checkMaintenance() {
        if ($this->config['CLOSED'] == 1) {
            $this->fatal_error('CLOSED_FOR_MAINTENANCE');
            exit();
        }
    }

    public function save_moment($text) {
        $newtime = $this->microtime_float() - $this->timestart;
        $this->timegauge[] = array('time' => $newtime, 'text' => $text);
    }

    // Return timestamp with microsecond precision
    private function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }

    public function route($request) {
        $r = $this; // introduce runtime for all actions that get injected
        $f = $request['f'];
        $i = $request['i'];
        $json = $request['json'];
        $p = $request['p'];

        if ($f != '' && $filename = $this->check_action($f)) {
            $this->save_moment("Started to include action $f");
            $module = $this->module; // introduce $module for action
            require $filename;
            $this->save_moment("Finished to include action $f");
            exit();
        } elseif ($i != '' && $filename = $this->check_action($i)) {
            print $r->render_ajaxflash();
            $this->save_moment("Started to include action $i");
            $module = $this->module; // introduce $module for action
            require $filename;
            $this->save_moment("Finished to include action $i");
            exit();
        } elseif ($json != '' && $filename = $this->check_action($json)) {
            $this->save_moment("Started to include action $json");
            $output = array();
            $module = $this->module; // introduce $module for action
            require $filename;
            print json_encode($output);
            $this->save_moment("Finished to include action $json");
            exit();
        } elseif ($p != '' && $filename = $this->check_action($p)) {
            $this->save_moment("Started to include action $p");
            require $this->config['folders']['include'].'CPage.php';
            $page = new CPage($r, 'main:index');
            $page->add('lastPage', $_SERVER['QUERY_STRING']);
            $page->add('msg', $r->render_flash());
            $module = $this->module; // introduce $module for action
            require $filename;
            $this->save_moment("Ready to render full page");
            print $page->render();
            $this->save_moment("Finished to include action $p");
            exit();
        }
        // if request is not routed yet, most probably it is invalid. Reroute it to default
        if ($this->attempt == 1) {
            $request['p'] = $this->config['DEFAULTPAGE'];
            $this->attempt++;
            $this->route($request);
        } else {
            $this->fatal_error('REDIRECT_LOOP');
        }
    }

    public function check_action($fullaction) {
        list($module, $action) = split('/', $fullaction, 2);
        if ($module != '' && $action != '') {
            $filename = "modules/$module/actions/$action.php";
            if (!file_exists($filename)) {
                $this->save_moment("Did not find the file for action $fullaction");
                return FALSE;
            }
            // Check unauthorized requests
            if (!$this->checkAuthorization($module, $action)) {
                $this->save_moment("Authorization check failed for action $fullaction");
                return FALSE;
            }
        } else {
            $this->module = $module;
            $this->action = $action;
            $this->save_moment("Malformed action $fullaction");
            return FALSE;
        }
        return $filename;
    }

    public function checkAuthorization($module, $action) {
        // TODO implement logic that restricts routing if action is not whitelisted
        return TRUE;
    }
    
    public function go($uri) {
        header("Location:$uri");
        die(); // prevent other code from executing
    }

    public function fatal_error($error) {
        switch ($error) {
            case 'REDIRECT_LOOP': 
                // TODO send email to site admin
                die(); 
            case 'DB_CONNECT_FAILED':
            case 'CLOSED_FOR_MAINTENANCE': 
                $errorPage = lavnn($error, $this->config['errorpages'], '');
                if ($errorPage == '') {
                    // TODO send email to site admin
                    die();
                } else {
                    $this->go($errorPage);
                }
                break;
            default: 
                // TODO send email to site admin
                die('Unknown fatal error occured');
                break;
        }
    }

    public function render_ajaxflash() {
        $output = '';
        if (array_key_exists('ajaxflash', $_SESSION)) {
            $output .= $this->txt->do_template('main', 'flash', array('flash' => $_SESSION['ajaxflash']));
            unset($_SESSION['ajaxflash']);
        }
        if (array_key_exists('ajaxerror', $_SESSION)) {
            $output .= $this->txt->do_template('main', 'error', array('error' => $_SESSION['ajaxerror']));
            unset($_SESSION['ajaxerror']);
        }
        return $output;
    }

    public function render_flash() {
        $output = '';
        if (array_key_exists('flash', $_SESSION)) {
            $output .= $this->txt->do_template('main', 'flash', array('flash' => $_SESSION['flash']));
            unset($_SESSION['flash']);
        }
        if (array_key_exists('error', $_SESSION)) {
            $output .= $this->txt->do_template('main', 'error', array('error' => $_SESSION['error']));
            unset($_SESSION['error']);
        }
        return $output;
    }

    public function getDictValues($modulename, $dictname, $lang) {
        $fulldictname = "$modulename:$dictname:$lang";
        if (array_key_exists($fulldictname, $this->dictionarycache)) {
            $dictvalues = $this->dictionarycache[$fulldictname];
        } else {
            $dictvalues = $this->getLabels($modulename, "$lang/$dictname");
            $this->dictionarycache[$fulldictname] = $dictvalues;
        }
        return $dictvalues;
    }

    public function getSetting($setting) {
        return lavnn($setting, $this->config, '');
    }
    
    /* Shortcus for text processing methods */

    /* Shortcus for database methods */
    public function s2r($modulename, $templatename, $sqlparams) {
        $this->db->sql2row($modulename, $templatename, $sqlparams);
    }

    public function s2a($modulename, $templatename, $sqlparams) {
        $this->db->sql2array($modulename, $templatename, $sqlparams);
    }

    public function spreview($modulename, $templatename, $sqlparams) {
        $this->db->preview($modulename, $templatename, $sqlparams);
    }
    
    public function sinsert($modulename, $templatename, $sqlparams) {
        $this->db->insert($modulename, $templatename, $sqlparams);
    }
    
    public function sdelete($modulename, $templatename, $sqlparams) {
        $this->db->delete($modulename, $templatename, $sqlparams);
    }

    public function supdate($modulename, $templatename, $sqlparams) {
        $this->db->update($modulename, $templatename, $sqlparams);
    }
}

?>