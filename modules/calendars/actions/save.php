<?php

use objCalendar;
$objCal = new objCalendar($r);

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $objCal->save_calendar(%_REQUEST);
  go("?p=$module/view&id=$id&tab=month");
} else {
  $_REQUEST['creator'] = $r['userID'];
  $_REQUEST['object_type'] = 'employee';
  $_REQUEST['object_id'] = $r['userID'];
  $id = $objCal->create_calendar(%_REQUEST);
  if ($id > 0) {
    $_SESSION['flash'] = 'Calendar created');
    go("?p=$module/view&id=$id&tab=month");
  } else {
    set_cookie('error', 'Could not add calendar');
  }
}
go("?p=$module/mycalendars");

?>
