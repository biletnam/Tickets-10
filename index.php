<?php

error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL || E_PARSE);

require 'config.php';
require $config['folders']['sitecode'].'dbconfig.php';

require $config['folders']['include'].'CRuntime.php';
require $config['folders']['include'].'CDatabase.php';
require $config['folders']['include'].'CTextProcessor.php';

require $config['folders']['include'].'Arrays.php';

$r = new CRuntime($config);
$r->checkMaintenance();
$txt = $r->txt = new CTextProcessor($r);
$db = $r->db = new CDatabase($r);
session_start(); 
$r->route($_REQUEST);

/*

use CFileSystem;
our $fs = new CFileSystem($r);
$runtime->set_filesystem($fs);
$runtime->saveMoment('filesystem object created and added to runtime context');

use CTextProcessor;
our $txt = new CTextProcessor($r);
our $lang = lavnn("lang", $_REQUEST, ''); 
$lang = $runtime->get_cookie('lang') if $lang == '';
$lang = '' if ($lang <> 'en' && $lang <> 'ru');
$lang = 'en' if trim($lang) == '';
$runtime->set_cookie('lang', $lang);
$txt->set_lang($lang);
$runtime->set_textprocessor($txt);
$runtime->saveMoment("textprocessor object created and added to runtime context, language set: $lang");

# Handle unauthorized requests
our $sessionID = lavnn('sessionID') || get_cookie("sessionID");
our $thispage = $runtime->urlencode($ENV['QUERY_STRING']);
if ($runtime->check_login($sessionID) == 0) {
  $url = array(lavnn('p') ? $thispage : '');
  if ($i <> '') {
    $urlAuthorize = $_CONFIG['UNAUTHORIZED_INLINE'];
    $runtime->saveMoment('  invalid session detected for inline action. Should redirect to '.$urlAuthorize);
    print "Location: $urlAuthorize\n\n";
    exit();
  } else {
    $runtime->saveMoment('  invalid session detected. Should redirect to login page with return to '.$url);
    go("?p=users/login&sess=$sessionID&url=$url");
    exit();
  }
}
$runtime->saveMoment('session checked');

use CAccessChecker;
our $acc = new CAccessChecker($r);
$runtime->set_accesschecker($acc);
$runtime->saveMoment('accesschecker object created and added to runtime context');

if ($fwd <> '') {
  $gotoUrl = $runtime->decode_fwd($_REQUEST);
  go($gotoUrl) if $gotoUrl <> '';
} elseif ($css <> '') {
  $filename = "css/$css.css";
  print "content-type: text/css\n\n";
  print $fs->getFileContents($filename);
  exit;
} elseif ($js <> '') {
  $filename = "js/$js.js";
  print "content-type: text/javascript\n\n";
  print $fs->getFileContents($filename);
  exit;
} elseif ($json <> '') {
  print "content-type: application/json\n\n";
  ($controller, $action) = split(/\//, $json);
  $filename = "modules/$controller/actions/$action.pl";
  if (-e $filename) {
    require $filename;
  }
  exit;
} elseif ($csv <> '') {
  ($controller, $action) = split(/\//, $csv);
  if ($controller <> '' && $action <> '') {
    $libraryname = "modules/$controller/lib.pl";
    if (-e $libraryname) {
      require $libraryname; 
    }
    $filename = "modules/$controller/actions/$action.pl";
    if (-e $filename) {
      print "Content-Description: File Transfer";
      print "content-type: application/csv\n\n";
#      print "Content-disposition: attachment; filename=new_central_export.csv";
      require $filename;
    }
  }
  exit;
} elseif ($api <> '') {
  $runtime->do_api($api, $_REQUEST);
  exit;
} elseif ($pdf <> '') {
  ($controller, $action) = split(/\//, $pdf);
  if ($controller <> '' && $action <> '') {
    $libraryname = "modules/$controller/lib.pl";
    if (-e $libraryname) {
      require $libraryname; 
    }
    $filename = "modules/$controller/actions/$action.pl";
    require $filename;
  }
  exit;
} elseif ($xml <> '') {
  ($controller, $action) = split(/\//, $xml);
  if ($controller <> '' && $action <> '') {
    $libraryname = "modules/$controller/lib.pl";
    if (-e $libraryname) {
      require $libraryname; 
    }
    print "content-disposition: attachment; filename=$action.xml\n";
    print "content-type: text/xml\n\n";
#    binmode STDOUT, ":utf8";
#    print "content-type: text/html; charset=utf-8;\n\n"; 
    $filename = "modules/$controller/actions/$action.pl";
    require $filename;
  }
  exit;
} elseif ($f <> '') {
  ($controller, $action) = split(/\//, $f);
  if ($controller <> '' && $action <> '') {
    $libraryname = "modules/$controller/lib.pl";
    if (-e $libraryname) {
      require $libraryname; 
    }
    $filename = "modules/$controller/actions/$action.pl";
    # TODO check if not only $filename exists, but $action is enabled in $controller
    # TODO also check if current user is allowed to access this $action or $controller
    if (-e $filename) {
      $runtime->saveMoment("form request processing for f=$f started");
      require $filename; # form is responsible to relocate to correct page
    }
    set_cookie('sessionID', $sessionID);
  }
} elseif ($i <> '') {
  ($controller, $action) = split(/\//, $i);
  if ($controller <> '' && $action <> '') {
    $libraryname = "modules/$controller/lib.pl";
    if (-e $libraryname) {
      require $libraryname; 
    }
    $filename = "modules/$controller/actions/$action.pl";
    # TODO check if not only $filename exists, but $action is enabled in $controller
    # TODO also check if current user is allowed to access this $action or $controller
    if (-e $filename) {
      set_cookie('sessionID', $sessionID);
      binmode STDOUT, ":utf8";
      print "content-type: text/html; charset=utf-8;\n\n";
      $runtime->saveMoment("inline request processing for i=$i started");
      require $filename;
      $runtime->save_timegauge(0);
      exit;
    } else {
      print "No $filename";
    }
  }
} elseif ($p <> '') {
  our %page = array('titlebase' => 'Intranet');
  ($controller, $action) = split(/\//, $p);
  if ($controller <> '' && $action <> '') {
    require "menu.pl";
    # include module's library if exists
    $libraryname = "modules/$controller/lib.pl";
    if (-e $libraryname) {
      require $libraryname; 
    }
    $filename = "modules/$controller/actions/$action.pl";
    # TODO check if not only $filename exists, but $action is enabled in $controller
    # TODO also check if current user is allowed to access this $action or $controller
    if (-e $filename) {
      $errormessage = get_cookie("error", $_REQUEST, '');
      $flashmessage = get_cookie("flash", $_REQUEST, '');
      $page['easteregg'] = get_cookie("easteregg", $_REQUEST, '');
      if ($errormessage <> '') {
        $page['flash'] .= dotmod('main', 'error', array('error' => $errormessage));
      } 
      if ($flashmessage <> '') {
        $page['flash'] .= dotmod('main', 'flash', array('flash' => $flashmessage));
      }
      set_cookie('error', '');
      set_cookie('flash', '');
      set_cookie('easteregg', '');
      set_cookie('sessionID', $sessionID);
      $runtime->set_this_page($thispage);
      binmode STDOUT, ":utf8";
      print "content-type: text/html; charset=utf-8;\n\n"; 
      $runtime->saveMoment("page request processing for p=$p started");
      #include code for managing page menu
      $menuscriptname = "modules/$controller/menu.pl";
      if (-e $menuscriptname) {
        require $menuscriptname; 
      }      
      # now, include the main code for page action
      require $filename;
      $runtime->saveMoment("page request processing for p=$p finished");
      $runtime->save_timegauge(1);
      exit;
    }
  }
}

$isexternal = array($r['userInfo']['additionalData']['dashboard_isexternal'] <> 1 && $r['userInfo']['lngWorkPlace'] == -1234) ? "0" : "1";
$defaultUrl = $isexternal ? $_CONFIG['DEFAULT_URL_EXTERNAL'] : $_CONFIG['DEFAULT_URL_INTERNAL'];  
# if control flow reached this point, it means that either none or invalid action requested
print "Location: $defaultUrl \n\n";
*/

?>
