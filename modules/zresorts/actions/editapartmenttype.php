<?php

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $runtime->s2r($module, 'GetHotelApartmentTypeInfo', array('id' => $id));
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $pageParams['hotel'])));
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'editapartmenttype.title', $pageParams); 
} else {
  %pageParams = array('hotel' => lavnn('hotel'), 'block' => lavnn('block'));
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $pageParams['hotel'])));
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'editapartmenttype.new.title', $pageParams); 
}
$arrtypeoptions = arr2ref(s2a($module, 'ListAbsoluteApartmentTypes'));
$pageParams['apttypeoptions'] = arr2ref(genOptions($arrtypeoptions, 'type_id', 'type_name', lavnn('abs_type')));
$page->add('main', $r->txt->do_template($module, 'editapartmenttype', $pageParams);




?>
