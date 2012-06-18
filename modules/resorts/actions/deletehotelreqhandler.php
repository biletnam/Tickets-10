<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $reqhandlerInfo = $runtime->s2r($module, 'GetHotelReqHandlerInfo', $_REQUEST);
  if (count($reqhandlerInfo) > 0) {
    $hotelInfo = $runtime->s2r($module, 'GetHotelInfo', array('id' => $reqhandlerInfo['hotel_id']));
    if (count($hotelInfo) > 0) {
      $hotel = $hotelInfo['hotel_id'];
      srun($module, 'DeleteHotelReqHander', $_REQUEST);
      go("?p=$module/bookings&hotel=$hotel&tab=handlers");
    }
  }
}
# In all other cases, return to list of hotels
go("?p=$module/hotels");

?>
