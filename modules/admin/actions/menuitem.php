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
  
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page['js'] .= dotmod('main', 'linkpeople.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  $page->add('css',  dotmod('main', 'linkpeople.css');

  $page->add('title',  $menuitemInfo['pagetitle'] = $runtime->doTemplate($module, 'menuitem.title', $menuitemInfo);
  $page->add('main', $runtime->doTemplate($module, 'menuitem', $menuitemInfo);
} else {
  $page->add('title',  $menuitemInfo['pagetitle'] = $runtime->doTemplate($module, 'menuitem.title.new', $menuitemInfo);
  $page->add('main', $runtime->doTemplate($module, 'menuitem.new', $menuitemInfo);
}



  
?>
