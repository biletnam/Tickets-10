<?php

$client_id = lavnn('client');
$bookings = $runtime->s2a($module, 'ListClientBookings', array('client_id' => $client_id));
if (count($bookings) > 0) {
  $pageParams = array();
  $pageParams['bookings'] = $bookings;
  print $runtime->txt->do_template($module, 'bookings', $pageParams);
} else {
  print $runtime->txt->do_template($module, 'bookings.none');
}

?>
