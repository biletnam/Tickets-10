<?php
if ($acc->is_superadmin()) {
  $pageParams = array();
  
  $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.settings');
  
  # Get list of contact types and day types
  $contacttypes = $runtime->s2a($module, 'ListContactTypes');
  $pageParams['contacttypes'] = $contacttypes;
  $daytypes = $runtime->s2a($module, 'ListDayTypes');
  $pageParams['daytypes'] = $daytypes;
   
  use ctlTab;
  $tabSettings = new ctlTab($r, 'ctSettings');
  $tabSettings->addTab('contacttypes', dot('settings.tab.contacttypes'), dot('settings.contacttypes', $pageParams));
  $tabSettings->addTab('daytypes', dot('settings.tab.daytypes'), dot('settings.daytypes', $pageParams));
  $tabSettings->setDefaultTab(lavnn('tab') || 'contacttypes');
  $pageParams['tabcontrol'] = $tabSettings->getHTML();
  
  $page['js'] = $r->txt->do_template('main', 'tabcontrol.js');
  $page['css'] = $r->txt->do_template('main', 'tabcontrol.css');    
  $page->add('main', $r->txt->do_template($module, 'settings', $pageParams);
} else {
  $page->add('main', $r->txt->do_template($module, 'settings.notallowed', $pageParams);
}



?>
