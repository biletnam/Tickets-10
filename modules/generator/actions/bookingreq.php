<?php
$pageParams = $runtime->s2r($module, 'GetBookingRequestData', $_REQUEST);
$book_id = $pageParams['book_id'] || 0;
if ($book_id > 0) {
  use objBooking;
  $objB = new objBooking($r, $book_id);
  $pageParams['bookingsection'] = $runtime->txt->do_template($module, 'bookingreq.booking', ${$objB['bookingdata']});
} 
$xmlstr = $pageParams['morepeople'] || '';
if ($xmlstr <> '') {
  $morepeople = Arrays::xml2a($xmlstr, 'person');
  if (count($morepeople) > 0) {
    $pageParams['morepeople'] = $morepeople;
    $pageParams['morepeoplehtml'] = loopt('bookingreq.additionalperson', @morepeople);
  }
}
$page->add('main', $runtime->txt->do_template($module, 'bookingreq', $pageParams);
print $runtime->txt->do_template($module, 'index', $page);

?>
