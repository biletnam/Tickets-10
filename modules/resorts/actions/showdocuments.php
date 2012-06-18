<?php

$pageParams = array();
$ids = lavnn('hotels'); 
$hotels = $runtime->s2a($module, 'MultipleHotels', array('ids' => $ids)); 
$pageParams['hotels'] = $hotels;
$documents = $runtime->s2a($module, 'MultipleHotelDocs', array('ids' => $ids)); 
$pageParams['documents'] = $documents;
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.showdocuments', $pageParams);
$page->add('main', $runtime->doTemplate($module, 'showdocuments', $pageParams);




?>
