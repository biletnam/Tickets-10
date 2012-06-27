<?php

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
$hotel = lavnn('hotel', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $runtime->s2r($module, 'GetHotelPeriodInfo', array('id' => $id));
  $hotel = $pageParams['hotel'] || '';
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'edithotelperiod.title', $pageParams); 
} else {
  $pageParams['hotel'] = $hotel;
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'edithotelperiod.new.title', $pageParams); 
}
$pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $hotel)));
$page->add('main', $r->txt->do_template($module, 'edithotelperiod', $pageParams);



?>
