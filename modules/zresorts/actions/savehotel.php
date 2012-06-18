<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'UpdateHotel', $_REQUEST);
} else {
  $id = sid($module, 'InsertHotel', $_REQUEST);
}

go("?p=resorts/hotels");

1;

?>
