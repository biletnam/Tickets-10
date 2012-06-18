<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $apttypeInfo = $runtime->s2r($module, 'GetHotelApartmentTypeInfo', array('id' => $id));
  if (count($apttypeInfo) > 0) {
    $hotel = $apttypeInfo['hotel'];
    srun($module, 'DeleteHotelApartmentType', $_REQUEST);
    go("?p=resorts/hbsb&id=$hotel");
    exit();
  }
}
go("?p=resorts/hotels");

?>
