<?php

$id = lavnn('id');
if ($id > 0) {
  $objCal->delete_calendar($_REQUEST);
}
go("?p=$module/mycalendars");

?>
