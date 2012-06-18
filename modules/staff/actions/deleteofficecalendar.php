<?php

use objCalendar;
$objCal = new objCalendar($r);

$id = lavnn("id", $_REQUEST, 0);
$calendarInfo = $objCal->get_office_calendar(%_REQUEST);
$office_id = $calendarInfo['office_id'] || 0;
if ($office_id <> 0) {
  if (0 < $objCal->delete_office_calendar(('id' => $id))) {
    set_cookie('flash', 'Office calendar deleted');
  } else {
    set_cookie('error', 'Could not delete office calendar');
  }
  go("?p=$module/office&office=$office_id&tab=calendars");
} else {
  go("?p=$module/offices");
}

?>
