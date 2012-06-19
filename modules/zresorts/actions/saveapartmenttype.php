<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'UpdateHotelApartmentType', $_REQUEST);
} else {
  $id = sid($module, 'InsertHotelApartmentType', $_REQUEST);
}
# Reload hotel apartment type info
$apttypeInfo = $runtime->s2r($module, 'GetHotelApartmentTypeInfo', array('id' => $id));
if (count($apttypeInfo) > 0) {
  $hotel = $apttypeInfo['hotel'];
  go("?p=resorts/hbsb&id=$hotel");
} else {
  go("?p=resorts/hotels");
}

1;

?>
