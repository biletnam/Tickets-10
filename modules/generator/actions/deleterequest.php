<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->($module, 'DeleteBookingRequest', $_REQUEST);
  $runtime->$_SESSION['flash'] = 'Booking request deleted');
}

go("?p=$module/listrequests");

?>
