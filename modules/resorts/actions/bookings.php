<?php

use ctlTab;

$pageParams = array();
$id = lavnn('hotel');
if ($id <> '') {
  %pageParams = $runtime->s2r('resorts', 'GetHotelInfo', array('id' => $id)); 
  $pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'bookings.title', $pageParams); 
  $pageParams['locations'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListLocations')), 'id', 'location_name', $pageParams['location_id']));
}

$page['js'] .= dotmod('main', 'tabcontrol.js');
$page->add('css',  dotmod('main', 'tabcontrol.css');
$tabHotelBookings = new ctlTab($r, "tcHotelBookings");
if ($id <> '') {
  $languages = $runtime->s2a($module, 'ListBookingRequestLanguages');
  $pageParams['languages'] = arr2ref(genOptions($languages, 'id', 'name'));

  # Booking request handlers
  $pageParams['handlers'] = arr2ref(s2a($module, 'ListHotelReqHandlers', array('id' => $id)));  
  $tabHotelBookings->addTab('reqhandlers', dot('bookings.reqhandlers.tabheader'), dot('bookings.reqhandlers', $pageParams));
  $newreqs = $runtime->s2a($module, 'ListHotelNewBookReqs', array('id' => $id));

  # New booking requests
  $pageParams['newreqs'] = $newreqs;  
  $tabHotelBookings->addTab('newreqs', dot('bookings.newreqs.tabheader'), dot('bookings.newreqs', $pageParams));
}
$tabHotelBookings->setDefaultTab(lavnn('tab') || 'reqhandlers'); 
$pageParams['tabcontrol'] = $tabHotelBookings->getHTML();
$runtime->saveMoment('  tab control rendered');

$page->add('main', $runtime->doTemplate($module, 'bookings', $pageParams);



?>
