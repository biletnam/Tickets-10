<?php

$id = lavnn('id', $_REQUEST, 0);
$hotel = lavnn('hotel_id', $_REQUEST, 0);
if ($id > 0 && $hotel > 0) {
  $runtime->db->sqlrun($module, 'UpdateLocalType', $_REQUEST);
} else {
  $id = $runtime->sid($module, 'InsertLocalType', $_REQUEST);
}
if ($hotel > 0) {
  go("?p=resorts/edithotel&id=$hotel&tab=localtypes");
} else {
  go("?p=resorts/hotels");
}

1;

?>
