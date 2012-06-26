<?php

$booking = lavnn('booking', $_REQUEST, '');
$contract = lavnn('contract', $_REQUEST, '');

if ($booking <> '' && $contract <> '') {
  if (0 < $runtime->db->sqlrun($module, 'LinkBooking', $_REQUEST)) {
    $_SESSION['flash'] = 'Booking (re)linked ');
    delete_cookie('booking_search_source_type');
    delete_cookie('booking_search_source_id');
  } else {
    $_SESSION['error'] = 'Booking not (re)linked ');
  }
}

if ($contract <> '') {
  go("?p=$module/view&id=$contract");
} else {
  go("?p=$module/search");
}

?>
