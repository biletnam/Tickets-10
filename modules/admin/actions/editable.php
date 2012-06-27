<?php

$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'editables.title');
if ($acc->can_access_menu('admin-setup-tables')) {
  $tableInfo = array();
  $id = lavnn('id', $_REQUEST, 0);
  if ($id > 0) {
    %tableInfo = $runtime->s2r($module, 'GetEditableTableInfo', $_REQUEST); 
  }
  $tableInfo['datesoptions'] = arr2ref($runtime->getDictArr('main', 'yesno', $tableInfo['include_dates']));
  $tableInfo['numbersoptions'] = arr2ref($runtime->getDictArr('main', 'yesno', $tableInfo['include_numbers']));
  if ($id > 0) {

    use ctlTab;
    $tabEditable = new ctlTab($r, 'ctEditableTable');
  
    $tabEditable->addTab('edit', dot('editable.tab.edit', $menuitemInfo), dot('editable.edit', $tableInfo)); 
    $tabEditable->addTab('access', dot('editable.tab.access'), dot('editable.access', $tableInfo));  
    $tabEditable->setDefaultTab(lavnn('tab') || 'edit');
    
    $page['js'] .= $r->txt->do_template('main', 'tabcontrol.js');
    $page['js'] .= $r->txt->do_template('main', 'linkpeople.js');
    $page->add('css',  $r->txt->do_template('main', 'tabcontrol.css');
    $page->add('css',  $r->txt->do_template('main', 'linkpeople.css');
  
    $page->add('title',  $tableInfo['pagetitle'] = $r->txt->do_template($module, 'editable.title', $tableInfo);
    $tableInfo['tabcontrol'] = $tabEditable->getHTML();
  } else {
    $page->add('title',  $tableInfo['pagetitle'] = $r->txt->do_template($module, 'editable.title.new', $tableInfo);
    $tableInfo['tabcontrol'] = $r->txt->do_template($module, 'editable.edit', $tableInfo);
  }
  $page->add('main', $r->txt->do_template($module, 'editable', $tableInfo);
} else {
  $page->add('main', $r->txt->do_template($module, 'noaccess', $pageParams);
}
$pageParams  = array();


  

?>
