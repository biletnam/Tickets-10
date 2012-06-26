<?php

class ctlTab {
    private $runtime;
    private $controlname = '';
    public $tabs = array();
    public $defaultTab = '';
    public $customClass = '';

		public function __construct($r, $name, $tabs = array(), $default = '') {
      $this->runtime = $r;
      $this->controlname = $name;
      $this->tabs = $tabs;
      $this->defaultTab = $default || $this->tabs[0]['name'];
      $this->customClass = ''; 
		}
    
    function addTab($tabName, $tabHeader, $tabContent, $tabAlign = 'left') {
      if (count($this->tabs) == 0) {
        // This tab will become a default if it is first being added 
        $this->defaultTab = $tabName;
      }
      $this->tabs[$tabName] = array(
        'tabcontrolname' => $this->controlname,
        'tabid' => $tabName, 
        'tabheader' => $tabHeader, 
        'tabcontent' => $tabContent,
        'tabalign' => $tabAlign,
      );
    }
    
    function render($setTab = '') {
      if ($setTab == '') { // set default if tab was not explicitly given
        $setTab = $this->defaultTab;
      } elseif (!array_key_exists($setTab, $this->tabs)) { // check if given tab exist
        $setTab = $this->defaultTab;
      }
      if (count($this->tabs) > 0) {
        return $this->runtime->doTemplate('framework', 'tabcontrol', array(
          'tabcontrolname' => $this->controlname, 
          'firstid' => $setTab,
          'tabs' => $this->tabs,
          'customtabclass' => $this->customClass,
        ));     
      } else {
        return '';     
      }
    }
}

?>