<?php

$pageParams = array();
$ids = lavnn('hotels'); 
$hotels = $runtime->s2a($module, 'MultipleHotels', array('ids' => $ids)); 
$pageParams['hotels'] = $hotels;
$documents = $runtime->s2a($module, 'MultipleHotelDocs', array('ids' => $ids)); 
$pageParams['documents'] = $documents;
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.showdocuments', $pageParams);
$page->add('main', $r->txt->do_template($module, 'showdocuments', $pageParams);




?>
