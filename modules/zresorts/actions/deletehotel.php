<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'DeleteHotel', $_REQUEST);
}

go("?p=resorts/hotels");

1;

?>
