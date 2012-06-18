<?php

use ctlTab;

$bookingInfo = $runtime->s2r($module, 'GetBookingInfo', $_REQUEST);

# Add linking box
if (get_cookie('booking_search_source_type') <> '') {
  $linkingInfo = (
    'source_type' => get_cookie('booking_search_source_type'),
    'source_id' => get_cookie('booking_search_source_id'),
  );
  $bookingInfo['linking'] .= $runtime->doTemplate($module, 'booking.linking.'.$linkingInfo['source_type'], $linkingInfo); 
}
 
#print spreview($module, 'GetBookingInfo', $_REQUEST) . Dumper($bookingInfo);
if (count($bookingInfo) > 0) {
  $page['js'] = dotmod('main', 'tabcontrol.js');
  $page['js'] .= dotmod('main', 'linkpeople.js');
  $page['css'] = dotmod('main', 'tabcontrol.css');
  $page->add('css',  dotmod('main', 'linkpeople.css');

  $tabBookingView = new ctlTab($r, "tcBookingView");
  $bookingInfo['statusoptions'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListBookingStatuses')), 'status_id', 'status_name', $bookingInfo['status_id']));
  $bookingInfo['nqreasonoptions'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListNQReasonStatuses')), 'id', 'name', $bookingInfo['nq_reason_id']));
  $bookingInfo['arrtransferoptions'] = arr2ref($runtime->getDictArr('main', 'yesno', $bookingInfo['arrival_transfer'])); 
  $bookingInfo['deptransferoptions'] = arr2ref($runtime->getDictArr('main', 'yesno', $bookingInfo['departure_transfer'])); 
  # TODO Investigate if only subset of recordset should be shown in dropdowns
  $bookingInfo['generators'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListGenerators')), 'generator_id', 'safe_generator_name', $bookingInfo['generator_id']));
  $bookingInfo['senders'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListGenerators')), 'generator_id', 'safe_generator_name', $bookingInfo['sender_id']));
  $bookingInfo['hotels'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListHotels')), 'hotel_id', 'hotel_name', $bookingInfo['hotel_id']));
  $hotelInfo = $runtime->s2r($module, 'GetHotelInfo', array('id' => $bookingInfo['hotel_id']));
  if (count($hotelInfo) > 0) {
    $bookingInfo['airports'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListAirports', array('country_id' => $hotelInfo['country_id']))), 'id', 'airport_name', $bookingInfo['arrival_airport_id']));
  }
  $tabBookingView->addTab('details', dot('booking.details.tabheader'), dot('booking.details', $bookingInfo)); 
#  print Dumper($bookingInfo);
  $familymembers = $runtime->s2a($module, 'GetBookingFamily', $_REQUEST); 
  if (count($familymembers) > 0) {
    $bookingInfo['moreguests'] = $runtime->doTemplate($module, 'booking.people.family', array('book_id' => $bookingInfo['book_id'], 'familymembers' => $familymembers));
  }
  $tabBookingView->addTab('people', dot('booking.people.tabheader', $ticketInfo), dot('booking.people', $bookingInfo)); 
  $bookingInfo['user_id'] = $r['userID'];
  $bookingInfo['comments'] = arr2ref(s2a($module, 'ListBookingComments', $bookingInfo));
  $tabBookingView->addTab('comments', dot('booking.comments.tabheader', $bookingInfo), dot('booking.comments', $bookingInfo)); 
  $tabBookingView->addTab('payments', dot('booking.payments.tabheader', $bookingInfo), dot('booking.payments.wait', $bookingInfo)); 
  $tabBookingView->addTab('attachments', dot('view.attachments.tabheader', $bookingInfo), dot('view.attachments.wait', $bookingInfo));
  $tabBookingView->addTab('followers', dot('view.followers.tabheader', $bookingInfo), dot('view.followers.wait', $bookingInfo));
  $tabBookingView->setDefaultTab(lavnn('tab') || 'details'); 
  $bookingInfo['tabcontrol'] = $tabBookingView->getHTML();
  $runtime->saveMoment('  tab control rendered');
  
  $page->add('title',  $bookingInfo['pagetitle'] = $runtime->doTemplate($module, 'title.booking', $bookingInfo);
  $page->add('main', $runtime->doTemplate($module, 'booking', $bookingInfo);
} else {
  $page->add('main', $runtime->doTemplate($module, 'booking.notfound', $bookingInfo);
}





?>
