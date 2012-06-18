<?php

class CPage {

    public $r;
    private $blocks = array();
    public $masterTemplate = 'main:index';
    
    function __construct($r, $masterTemplatePath = '') {
        // Link back to runtime context
        $this->r = $r;
        // Set up master template
        if ($masterTemplatePath <> '') {
            $this->masterTemplate = $masterTemplatePath;
        }
        $r->save_moment('Page object created');
    }

    function add($block, $content) {
        $this->blocks[$block] .= $content;
    }

    function render() {
        list($modulename, $templatename) = split(':', $this->masterTemplate, 2);        
        return $this->r->txt->do_template($modulename, $templatename, $this->blocks);
    }

}

?>