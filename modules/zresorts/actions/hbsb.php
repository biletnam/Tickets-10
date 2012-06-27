<?php

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
if ($id > 0) {
  $pageParams['id'] = $id;
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', $_REQUEST));
  $pageParams['hb'] = arr2ref(s2a($module, 'ListApartments', array('id' => $id, 'block' => 'HB')));
  $pageParams['hbtypes'] = arr2ref(s2a($module, 'ListApartmentTypes', array('id' => $id, 'block' => 'HB')));
  $pageParams['sb'] = arr2ref(s2a($module, 'ListApartments', array('id' => $id, 'block' => 'SB')));
  $pageParams['sbtypes'] = arr2ref(s2a($module, 'ListApartmentTypes', array('id' => $id, 'block' => 'SB')));
}
$pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'hbsb.title', $pageParams);
$page->add('main', $r->txt->do_template($module, 'hbsb', $pageParams);




?>
