<?php

$id = lavnn('id', $_REQUEST, 0);
$hotel = 0;
if ($id > 0) {
  $hardblockInfo = $runtime->s2r($module, 'GetHardBlockInfo', $_REQUEST);
  if (count($hardblockInfo) > 0) {
    $hotel = $hardblockInfo['hotel_id'];
    srun($module, 'DeleteHardBlock', $_REQUEST);
  }
}
if ($hotel > 0) {
  go("?p=resorts/edithotel&id=$hotel&tab=hb");
} else {
  go("?p=resorts/hotels");
}

1;

?>
