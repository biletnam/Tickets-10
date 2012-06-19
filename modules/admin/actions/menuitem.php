<?php

$id = lavnn('id');
if ($id > 0) {
  $menuitemInfo = $runtime->s2r($module, 'GetMenuItemData', $_REQUEST);  

  use ctlTab;
  $tabMenuItem = new ctlTab($r, 'ctProduct');

  $tabMenuItem->addTab('edit', dot('menuitem.tab.edit', $menuitemInfo), dot('menuitem.edit', $menuitemInfo));
  $tabMenuItem->addTab('obligations', dot('menuitem.tab.access'), dot('menuitem.access', $menuitemInfo));  
  $tabMenuItem->setDefaultTab(lavnn('tab') || 'edit');
  $menuitemInfo['tabcontrol'] = $tabMenuItem->getHTML();
  
  $page['js'] .= $runtime->txt->do_template('main', 'tabcontrol.js');
  $page['js'] .= $runtime->txt->do_template('main', 'linkpeople.js');
  $page->add('css',  $runtime->txt->do_template('main', 'tabcontrol.css');
  $page->add('css',  $runtime->txt->do_template('main', 'linkpeople.css');

  $page->add('title',  $menuitemInfo['pagetitle'] = $runtime->txt->do_template($module, 'menuitem.title', $menuitemInfo);
  $page->add('main', $runtime->txt->do_template($module, 'menuitem', $menuitemInfo);
} else {
  $page->add('title',  $menuitemInfo['pagetitle'] = $runtime->txt->do_template($module, 'menuitem.title.new', $menuitemInfo);
  $page->add('main', $runtime->txt->do_template($module, 'menuitem.new', $menuitemInfo);
}



  
?>
