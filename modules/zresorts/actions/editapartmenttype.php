<?php

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $runtime->s2r($module, 'GetHotelApartmentTypeInfo', array('id' => $id));
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $pageParams['hotel'])));
  $pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'editapartmenttype.title', $pageParams); 
} else {
  %pageParams = ('hotel' => lavnn('hotel'), 'block' => lavnn('block'));
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $pageParams['hotel'])));
  $pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'editapartmenttype.new.title', $pageParams); 
}
$arrtypeoptions = arr2ref(s2a($module, 'ListAbsoluteApartmentTypes'));
$pageParams['apttypeoptions'] = arr2ref(genOptions($arrtypeoptions, 'type_id', 'type_name', lavnn('abs_type')));
$page->add('main', $runtime->doTemplate($module, 'editapartmenttype', $pageParams);




?>
