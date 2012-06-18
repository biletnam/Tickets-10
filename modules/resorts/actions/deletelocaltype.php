<?php

$id = lavnn('id', $_REQUEST, 0);
$hotel = 0;
if ($id > 0) {
  $localtypeInfo = $runtime->s2r($module, 'GetLocalTypeInfo', $_REQUEST);
  if (count($localtypeInfo) > 0) {
    $hotel = $localtypeInfo['hotel_id'];
    srun($module, 'DeleteLocalType', $_REQUEST);
  }
}
if ($hotel > 0) {
  go("?p=resorts/edithotel&id=$hotel&tab=localtypes");
} else {
  go("?p=resorts/hotels");
}

1;

?>
