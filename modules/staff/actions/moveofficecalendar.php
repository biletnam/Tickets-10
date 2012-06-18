<?php

use objCalendar;
$objCal = new objCalendar($r);

$result = $objCal->move_office_calendar(%_REQUEST);
if ($result > 0) {
  set_cookie('flash', 'Calendar moved to another office');
} else {
  set_cookie('error', 'Could not move the calendar');
}

$calendarInfo = $objCal->get_office_calendar(%_REQUEST);
$office_id = $calendarInfo['office_id'] || 0;
if ($office_id <> 0) {
  go("?p=$module/office&office=$office_id&tab=calendars");
} else {
  go("?p=$module/offices");
} 

?>