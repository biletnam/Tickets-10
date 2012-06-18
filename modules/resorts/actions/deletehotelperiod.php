<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $periodinfo = $runtime->s2r($module, 'GetHotelPeriodInfo', array('id' => $id));
  if (count($periodinfo) > 0) {
    $hotel = $periodinfo['hotel'] || 0;
    srun($module, 'DeleteHotelPeriod', $_REQUEST);
    go("?p=resorts/edithotelperiods&id=$hotel") if $hotel > 0;
  }
}

go("?p=resorts/hotels");

1;

?>
