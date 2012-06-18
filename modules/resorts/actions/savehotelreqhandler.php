<?php

$hotel = lavnn('hotel', $_REQUEST, 0);
if ($hotel > 0) {
  $id = sid($module, 'InsertHotelReqHandler', $_REQUEST);
  if ($id > 0) {
    $runtime->set_cookie('flash', 'Booking Request Handler added for this hotel');
  }
  go("?p=$module/bookings&hotel=$hotel&tab=handlers");
} else {
  go("?p=$module/hotels");
}

?>
