<?php

$searchInfo = %_REQUEST;

if (get_cookie('booking_search_source_type') <> '') {
  $linkingInfo = (
    'source_type' => get_cookie('booking_search_source_type'),
    'source_id' => get_cookie('booking_search_source_id'),
  );
  $searchInfo['linking'] .= $runtime->doTemplate($module, 'search.linking.'.$linkingInfo['source_type'], $linkingInfo); 
}

$generators = $runtime->s2a($module, 'ListGenerators');
$searchInfo['generators'] = arr2ref(genOptions($generators, 'generator_id', 'safe_generator_name', $_REQUEST['generator_id']));
$hotels = $runtime->s2a($module, 'ListHotels');
$searchInfo['hotels'] = arr2ref(genOptions($hotels, 'hotel_id', 'hotel_name', $_REQUEST['hotel_id']));
$yesno = $runtime->getDictArr('main', 'yesno', 0);
$searchInfo['hasmerlinoptions'] = arr2ref(genOptions($yesno, 'key', 'value', $_REQUEST['has_merlin_id']));
$datetypes = $runtime->getDictArr($module, 'datetype', 0);
$searchInfo['datetypeoptions'] = arr2ref(genOptions($datetypes, 'key', 'value', $_REQUEST['datetype']));
$locations = $runtime->s2a($module, 'ListOfficeLocations'); 
$searchInfo['locationoptions'] = arr2ref(genOptions($locations, 'location_id', 'location_name', $_REQUEST['location_id']));
$statuses = $runtime->s2a($module, 'ListBookingStatuses'); 
$searchInfo['statusoptions'] = arr2ref(genOptions($statuses, 'status_id', 'status_name', $_REQUEST['status_id']));

$page->add('title',  $searchInfo['pagetitle'] =  dot('title.search');
$resultsHtml = '';
if (dot('search.checkfields', $_REQUEST) <> '') {

  use ctlDataGrid;
  
  $basequery = spreview($module, 'SearchBookings', $_REQUEST);  print "<!-- $basequery -->";
  $grid1 = new ctlDataGrid($r, 'bookings', $basequery, $module);
  $descriptor = $runtime->rf($module, 'sql/SearchBookings.columns.txt');
  $columns = $grid1->parse_columns_descriptor($descriptor);
  $grid1->set_columns(@columns);
  $grid1->set_pager(50);
  $grid1->set_width('100%');
  $grid1->set_custom_template('actions' => $runtime->gettmod($module, 'search.listitem.actions'));
  $grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'search.none'));
  $resultsHtml = $grid1->render();
  $runtime->saveMoment('Finished rendering data grid');

  #$cc = $r['db']get_metadata($basequery); print Dumper($cc);

}
$searchInfo['results'] = $resultsHtml;
$page->add('main', $runtime->doTemplate($module, 'search', $searchInfo);



?>
