<?php

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $runtime->s2r($module, 'GetHotelApartmentInfo', array('id' => $id));
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $pageParams['hotel'])));
  $pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'editapartment.title', $pageParams); 
  $arrtypeoptions = arr2ref(s2a($module, 'ListHotelApartmentTypes', array('id' => $pageParams['hotel'])));
  $pageParams['apttypeoptions'] = arr2ref(genOptions($arrtypeoptions, 'id', 'name', lavnn('apartment_type')));
  $page->add('main', $runtime->txt->do_template($module, 'editapartment', $pageParams);
} else {
  %pageParams = array('hotel' => lavnn('hotel'), 'block' => lavnn('block'));
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $pageParams['hotel'])));
  $pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'addapartment.title', $pageParams);
  $arrtypeoptions = arr2ref(s2a($module, 'ListHotelApartmentTypes', array('id' => $pageParams['hotel'])));
  $pageParams['apttypeoptions'] = arr2ref(genOptions($arrtypeoptions, 'id', 'name', lavnn('apartment_type')));
  $page->add('main', $runtime->txt->do_template($module, 'addapartment', $pageParams);
}






?>
