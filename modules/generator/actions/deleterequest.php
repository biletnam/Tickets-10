<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->srun($module, 'DeleteBookingRequest', $_REQUEST);
  $runtime->set_cookie('flash', 'Booking request deleted');
}

go("?p=$module/listrequests");

?>
