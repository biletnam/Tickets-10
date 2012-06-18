<?php

use ctlDataGrid;

$pageParams = array();  
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.pageviews');

$viewertype = $pageParams['viewer_type'] = (lavnn('viewer_type') || '');
$viewertypes = $runtime->getDictArr('main', 'pageviewer.type', $viewertype);
$pageParams['viewertypes'] = $viewertypes;
$pageParams['viewer_id'] = lavnn('viewer_id');
$entitytype = $pageParams['entity_type'] = (lavnn('entity_type') || '');
$entitytypes = $runtime->s2a($module, 'PageviewEntityTypes');
$entitytypeoptions = genOptions($entitytypes, 'entity_type', 'entity_type', $entitytype);
$pageParams['entitytypes'] = $entitytypeoptions;
$pageParams['entity_id'] = lavnn('entity_id');

$basequery = spreview($module, 'Pageviews', $_REQUEST); 
$grid1 = new ctlDataGrid($r, 'pageviews', $basequery, $module);
$descriptor = $runtime->rf($module, 'sql/Pageviews.columns.txt');
$columns = $grid1->parse_columns_descriptor($descriptor);
$grid1->set_columns(@columns);
$grid1->set_pager(50);
$grid1->set_width('100%');
$grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'pageviews.listitem'));
$grid1->set_default_sort(-5);
$pageParams['results'] = $grid1->render();
$runtime->saveMoment('Finished rendering data grid');

$pageParams['reportname'] = $report;
$page->add('main', $runtime->doTemplate($module, 'pageviews', $pageParams);

$page['js'] .= $runtime->doTemplate($module, 'pageviews.js');



?>