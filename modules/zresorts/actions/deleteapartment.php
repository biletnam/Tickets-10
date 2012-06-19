<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $aptInfo = $runtime->s2r($module, 'GetHotelApartmentInfo', array('id' => $id));
  if (count($aptInfo) > 0) {
    $hotel = $aptInfo['hotel'];
    $runtime->db->sqlrun($module, 'DeleteHotelApartment', $_REQUEST);
    go("?p=resorts/hbsb&id=$hotel");
    exit();
  }
}
go("?p=resorts/hotels");

?>
