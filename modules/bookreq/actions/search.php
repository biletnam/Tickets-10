<?php

$searchInfo = $_REQUEST;

$generators = $runtime->s2a($module, 'ListGenerators'); # TODO Bookreq specific - not all are visible to user!
$searchInfo['generators'] = arr2ref(genOptions($generators, 'generator_id', 'safe_generator_name', $_REQUEST['generator_id']));
$hotels = $runtime->s2a($module, 'ListHotels', array('user_id' => $r['userID']));
$searchInfo['hotels'] = arr2ref(genOptions($hotels, 'hotel_id', 'hotel_name', $_REQUEST['hotel_id']));
$statuses = $runtime->getSortedDictArr('bookreq', 'bookreq.status', $_REQUEST['status']); 
$searchInfo['statuses'] = $statuses;
$languages = $runtime->s2a($module, 'ListBookReqLanguages');
$searchInfo['languages'] = arr2ref(genOptions($languages, 'id', 'name', $_REQUEST['language']));
$_REQUEST['user_id'] = $r['userID'];

$page->add('title',  $searchInfo['pagetitle'] =  dot('title.search');
$resultsHtml = '';

use ctlDataGrid;
$basequery = $runtime->spreview($module, 'SearchBookReqs', $_REQUEST);
$grid1 = new ctlDataGrid($r, 'bookreqs', $basequery, $module);
$descriptor = $runtime->txt->get_module_file($module, 'sql/SearchBookReqs.columns.txt');
$columns = $grid1->parse_columns_descriptor($descriptor);
$grid1->set_columns(@columns);
$grid1->set_pager(50);
$grid1->set_width('100%');
$grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'search.listitem'));
$grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'search.none'));
$resultsHtml = $grid1->render();
$runtime->saveMoment('Finished rendering data grid');

$searchInfo['results'] = $resultsHtml;
$page->add('main', $runtime->txt->do_template($module, 'search', $searchInfo);



?>
