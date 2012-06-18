<?php

use ctlDataGrid;

$pageParams = array();  
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.pageviews');

$basequery = spreview($module, 'Pageviews', $_REQUEST); 
$grid1 = new ctlDataGrid($r, 'pageviews', $basequery, $module);
$descriptor = $runtime->rf('reports', 'sql/Pageviews.columns.txt');
$columns = $grid1->parse_columns_descriptor($descriptor);
$grid1->set_columns(@columns);
$grid1->set_pager(50);
$grid1->set_width('100%');
$grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'pageviews.listitem'));
$pageParams['results'] = $grid1->render();
$runtime->saveMoment('Finished rendering data grid');

print dot('pageviews', $pageParams);

?>
