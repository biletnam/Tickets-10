<?php

use objCalendar;
$objCal = new objCalendar($r);

$newid = $objCal->clone_office_calendar($_REQUEST);
if ($newid > 0) {
  $_SESSION['flash'] = 'Calendar cloned');
  go("?p=$module/officecalendar&id=$newid");
} else {
  $_SESSION['error'] = 'Could not clone the calendar');
  $calendarInfo = $objCal->get_office_calendar($_REQUEST);
  $office_id = $calendarInfo['object_id'] || 0;
  if ($office_id <> 0) {
    go("?p=$module/office&office=$office_id&tab=calendars");
  } else {
    go("?p=$module/offices");
  } 
}

?>
