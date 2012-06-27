<?php

use ctlDataGrid;

$pageParams = array();  
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.pageviews');

$viewertype = $pageParams['viewer_type'] = array(lavnn('viewer_type') || '');
$viewertypes = $runtime->getDictArr('main', 'pageviewer.type', $viewertype);
$pageParams['viewertypes'] = $viewertypes;
$pageParams['viewer_id'] = lavnn('viewer_id');
$entitytype = $pageParams['entity_type'] = array(lavnn('entity_type') || '');
$entitytypes = $runtime->s2a($module, 'PageviewEntityTypes');
$entitytypeoptions = genOptions($entitytypes, 'entity_type', 'entity_type', $entitytype);
$pageParams['entitytypes'] = $entitytypeoptions;
$pageParams['entity_id'] = lavnn('entity_id');

$basequery = $runtime->spreview($module, 'Pageviews', $_REQUEST); 
$grid1 = new ctlDataGrid($r, 'pageviews', $basequery, $module);
$descriptor = $runtime->txt->get_module_file($module, 'sql/Pageviews.columns.txt');
$columns = $grid1->parse_columns_descriptor($descriptor);
$grid1->set_columns(@columns);
$grid1->set_pager(50);
$grid1->set_width('100%');
$grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'pageviews.listitem'));
$grid1->set_default_sort(-5);
$pageParams['results'] = $grid1->render();
$runtime->saveMoment('Finished rendering data grid');

$pageParams['reportname'] = $report;
$page->add('main', $r->txt->do_template($module, 'pageviews', $pageParams);

$page['js'] .= $r->txt->do_template($module, 'pageviews.js');



?>
