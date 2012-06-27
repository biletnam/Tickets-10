<?php
$searchInfo = $_REQUEST;
$page->add('title',  $searchInfo['pagetitle'] =  dot('title.search');
$resultsHtml = '';
$offices = $runtime->s2a($module, 'ListAllBookingOffices'); 
$searchInfo['officeoptions'] = arr2ref(genOptions($offices, 'lngId', 'strName', $_REQUEST['office']));
use ctlDataGrid;

$basequery = $runtime->spreview($module, 'SearchGenerators', $_REQUEST); 
$grid1 = new ctlDataGrid($r, 'generators', $basequery, $module);
$descriptor = $runtime->txt->get_module_file($module, 'sql/SearchGenerators.columns.txt');
$columns = $grid1->parse_columns_descriptor($descriptor);
$grid1->set_columns(@columns);
$grid1->set_pager(20);
$grid1->set_width('100%');
$grid1->set_custom_template('actions' => $runtime->gettmod($module, 'search.listitem.actions'));
$grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'search.none'));
$resultsHtml = $grid1->render(('rowcount' => 'yes'));
  
$searchInfo['results'] = $resultsHtml;
$page->add('main', $r->txt->do_template($module, 'search', $searchInfo);




?>
