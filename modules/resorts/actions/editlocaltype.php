<?php

use ctlTab;

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
$hotel = lavnn('hotel', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $runtime->s2r($module, 'GetLocalTypeInfo', array('id' => $id)); 
  $hotel = $pageParams['hotel_id'];
} elseif($hotel <> '') {
  %pageParams = array('hotel_id' => $hotel);
}
if ($hotel <> '') {
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $hotel))); 
  $arrtypeoptions = arr2ref(s2a($module, 'ListAptTypes'));
  $pageParams['apttypes'] = arr2ref(genOptions($arrtypeoptions, 'type_id', 'type_name', $pageParams['apt_type_id']));
  $pageParams['yesno'] = arr2ref($runtime->getDictArr('main', 'yesno', $pageParams['in_use']));
}
if ($id <> '') {
  # Editing local type 
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'title.localtype.edit', $pageParams);
  $page->add('main', $r->txt->do_template($module, 'editlocaltype', $pageParams);
} else {
  # Just adding local type -> can't show anything but create form
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'title.localtype.add', $pageParams);
  $page->add('main', $r->txt->do_template($module, 'editlocaltype', $pageParams);
}




?>
