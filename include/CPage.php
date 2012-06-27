<?php

class CPage {

    public $r;
    private $blocks = array();
    public $masterTemplate = 'main:index';
    
    function __construct($r, $masterTemplatePath = '') {
        // Link back to runtime context
        $this->r = $r;
        // Set up master template
        $this->set_master_template($masterTemplatePath);
        
        $r->save_moment('Page object created');
    }

    function set_master_template($masterTemplatePath = '') {
        if ($masterTemplatePath <> '') {
            $this->masterTemplate = $masterTemplatePath;
        }
    }
     
    function add($block, $content) {
        $this->blocks[$block] .= $content;
    }

    function add_cssfile($filename) {
        $this->blocks['cssfiles'] .= $this->r->txt->do_template('framework', 'cssfile', array('filename' => $filename));
    }

    function add_jsfile($filename) {
        $this->blocks['jsfiles'] .= $this->r->txt->do_template('framework', 'jsfile', array('filename' => $filename));
    }

    function render($masterTemplatePath = '') {
        $this->set_master_template($masterTemplatePath);
        list($modulename, $templatename) = split(':', $this->masterTemplate, 2);        
        return $this->r->txt->do_template($modulename, $templatename, $this->blocks);
    }

}

?>