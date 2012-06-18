<?php

use ctlDataGrid;

$pageParams = array();  
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.loginfailures');

@alltypes = $runtime->getSortedDictArr('main', 'pageviewer.type'); $ids = ''; 
if (ref($_REQUEST['typeoption']) == 'ARRAY') {
  $selectedtypes = $_REQUEST['typeoption']; 
  $ids = $_REQUEST['typeoptions'] = join(',', @selectedtypes); 
} else {
  $ids = $_REQUEST['typeoptions'] = $_REQUEST['typeoption'];
}
$pageParams['typecheckboxes'] = arr2ref(genCheckboxes($alltypes, 'typeoption', 'key', 'value', $ids));

$basequery = spreview($module, 'LoginFailures', $_REQUEST); 
$grid1 = new ctlDataGrid($r, 'loginfailures', $basequery, $module);
$descriptor = $runtime->rf($module, 'sql/LoginFailures.columns.txt');
$columns = $grid1->parse_columns_descriptor($descriptor);
$grid1->set_columns(@columns);
$grid1->set_pager(20);
$grid1->set_width('100%');
$grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'loginfailures.listitem'));
$pageParams['results'] = $grid1->render();
$runtime->saveMoment('Finished rendering data grid');

$page->add('main', $runtime->txt->do_template($module, 'loginfailures', $pageParams);

$page['js'] .= $runtime->txt->do_template($module, 'pageviews.js');



?>
