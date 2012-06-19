<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'UpdateHotel', $_REQUEST);
} else {
  $id = sid($module, 'InsertHotel', $_REQUEST);
}

go("?p=resorts/hotels");

1;

?>
