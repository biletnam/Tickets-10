<?php

use ctlDataGrid;

$pageParams = array();  
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.expiringdocs');

$doctypes = $runtime->s2a('admin', 'ListDocTypes');
$pageParams['doctypes'] = arr2ref(genOptions($doctypes, 'id', 'name', lavnn('document_type')));
$pageParams['daysbefore'] = $_REQUEST['daysbefore'] ||= 7;
$pageParams['daysafter'] = $_REQUEST['daysafter'] ||= 30;
$basequery = spreview($module, 'ExpiringDocuments', $_REQUEST); #print $basequery;
$grid1 = new ctlDataGrid($r, 'expiringdocs', $basequery, $module);
$descriptor = $runtime->rf($module, 'sql/ExpiringDocuments.columns.txt');
$columns = $grid1->parse_columns_descriptor($descriptor);
$grid1->set_columns(@columns);
$grid1->set_pager(20);
$grid1->set_width('100%');
$grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'expiringdocs.listitem'));
$pageParams['results'] = $grid1->render();
$runtime->saveMoment('Finished rendering data grid');

$page->add('main', $runtime->txt->do_template($module, 'expiringdocs', $pageParams);



?>
