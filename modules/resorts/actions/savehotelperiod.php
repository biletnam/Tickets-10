<?php

$id = lavnn('id', $_REQUEST, 0);
$hotel = lavnn('hotel', $_REQUEST, 0);
if ($hotel > 0) {
  if ($id > 0) {
    $runtime->db->sqlrun($module, 'UpdateHotelPeriod', $_REQUEST);
  } else {
    $id = $runtime->sid($module, 'InsertHotelPeriod', $_REQUEST);
  }
  go("?p=resorts/hotelperiods&id=$hotel");
} else {
  go("?p=resorts/hotels");
}

1;

?>
