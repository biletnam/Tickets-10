<?php

require "CScalarFilter.php";
require "CArrayFilter.php";
require "CObjectFilter.php";
require "CNumericFilter.php";

class CTextProcessor {

    public function __construct($r) {
        // Link back to runtime context
        $this->r = $r;
        $this->scalarFilter = new CScalarFilter($this);
        $this->arrayFilter = new CArrayFilter($this);
        $this->objectFilter = new CObjectFilter($this);
        $this->numericFilter = new CNumericFilter($this);
        $r->save_moment('Text processor connected');
    }

    public function do_sql_template($modulename, $templatename, $params = array()) {
        // get contents of template file, addressed by $modulename and $templatename
        $template = $this->get_sql_template($modulename, $templatename);
        if ($template != '') {
            // process the text
            return $this->do_text($template, $params);
        } else
            return '';
    }

    public function get_sql_template($modulename, $templatename) {
        $filename = "modules/$modulename/sql/$templatename.sql";
        if (file_exists($filename)) {
            return file_get_contents($filename);
        } else {
            $this->r->save_moment("Could not find the SQL template $modulename/$templatename");
            return '';
        }
    }

    public function loop_template($modulename, $templatename, $rows = array()) {
        // get contents of template file, addressed by $modulename and $templatename
        $template = $this->get_template($modulename, $templatename);
        if ($template != '') {
            // process the text in cycle
            $output = array();
            foreach ($rows as $row) {
                $output[] = $this->do_text($template, $row);
            }
            return join('', $output);
        } else
            return '';
    }

    public function do_template($modulename, $templatename, $params = array()) {
        // get contents of template file, addressed by $modulename and $templatename
        $template = $this->get_template($modulename, $templatename);
        if ($template != '') {
            // process the text
            return $this->do_text($template, $params);
        } else
            return '';
    }

    public function get_template($modulename, $templatename) {
        $filename = "modules/$modulename/templates/$templatename.html";
        if (file_exists($filename)) {
            return file_get_contents($filename);
        } else {
            $this->r->save_moment("Could not find the HTML template $modulename/$templatename");
            return '';
        }
    }

    public function get_module_file($modulename, $filename) {
        $filename = "modules/$modulename/$filename";
        if (file_exists($filename)) {
            return file_get_contents($filename);
        } else {
            $this->r->save_moment("Could not find the module file $modulename/$filename");
            return '';
        }
    }

    public function get_dictionary($modulename, $dictname) {
        $output = array();
        foreach (explode("\n", $this->get_module_file($modulename, "dict/$dictname.txt")) as $line) {
            if (trim($line) != '') {
                list($key, $value) = explode('=', trim($line), 2);
                if ($key != '' && !array_key_exists($key, $output) && $value != '') {
                    $output[$key] = array(
                        'key' => $key,
                        'value' => $value,
                    );
                }
            }
        }
        return $output;
    }

    public function do_text($text, $params = array()) {
        $parts = explode('<:', $text); // beginnings of all placeholders
        $output = array_shift($parts); // text before first placeholder
        foreach ($parts as $part) {
            list($tag, $statictext) = explode('>', $part, 2); // placeholder and text before the next one
            list($tagname, $tagvalue) = explode(':', $tag, 2); // tag name and tag value to process
            $output .= ( $this->do_tag($tagname, $tagvalue, $params) . $statictext);
        }
        return $output;
    }

    public function do_tag($tagname, $tagvalue, $params = array()) {
        // extract filters from placeholder definition, if any
        $filters = explode("|", $tagvalue);
        $tagvalue = array_shift($filters);
        // process tags according to their name
        switch ($tagname) {
            case '_': // based on no value - simple way to include child templates
                $value = '';
                $output = (count($filters) > 0) ? $this->do_filter($value, $filters, $params) : $value;
                break;
            case 'v': // named element from associative array $params
                $value = $params[$tagvalue];
                $output = (count($filters) > 0) ? $this->do_filter($value, $filters, $params) : $value;
                break;
            case 'a': // named element (of array type) from associative array $params
                $value = $params[$tagvalue];
                $output = (count($filters) > 0) ? $this->do_filter($value, $filters, $params) : $value;
                break;
            case 'srv':
            case 'server': // named element from $_REQUEST array
                $value = $_SERVER[$tagvalue];
                $output = (count($filters) > 0) ? $this->do_filter($value, $filters, $params) : $value;
                break;
            case 'req':
            case 'request': // named element from $_REQUEST array
                $value = $_REQUEST[$tagvalue];
                $output = (count($filters) > 0) ? $this->do_filter($value, $filters, $params) : $value;
                break;
            case 'sess':
            case 'session': // named element from $_SESSION array
                $value = $_SESSION[$tagvalue];
                $output = (count($filters) > 0) ? $this->do_filter($value, $filters, $params) : $value;
                break;
            case 'cookie': // named cookie
                $value = $_COOKIE[$tagvalue];
                $output = (count($filters) > 0) ? $this->do_filter($value, $filters, $params) : $value;
                break;
            case 'cfg':
            case 'config': // named element from configuration array as runtime object knows it
                $value = $this->r->config[$tagvalue];
                $output = (count($filters) > 0) ? $this->do_filter($value, $filters, $params) : $value;
                break;
        }
        return $output;
    }

    public function do_filter($value, $filters, $params = array()) {
        if (is_object($value)) {
            return $this->objectFilter->apply($value, $filters, $params);
        } else if (is_array($value)) {
            return $this->arrayFilter->apply($value, $filters, $params);
        } else if (is_numeric($value)) {
            return $this->numericFilter->apply($value, $filters, $params);
        } else {
            return $this->scalarFilter->apply($value, $filters, $params);
        }
    }

}

?>