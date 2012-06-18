<?php
use objCalendar;
$objCal = new objCalendar($r);

$nocache = sprintf("%f", time());
$id = lavnn('id', $_REQUEST, '');
$year = lavnn('year', $_REQUEST, '');
$month = lavnn('month', $_REQUEST, '');
if ($id == '' || $year == '' || $month == '') {
  set_cookie('error', 'Please provide calendar, date and comment to add event');  
  # do nothing to return empty string
} else {
  $calendarInfo = $runtime->s2r($module, 'GetCalendarData', array('id' => $id));
  $comment = lavnn('comment');
  if (count($calendarInfo) == 0) {
    set_cookie('error', 'Calendar was not found');
  } elseif ($comment == '') {
    set_cookie('error', 'Please provide comment to add event');  
    go("?i=$module/viewmonth&id=$id&year=$year&month=$month&nocache=$nocache");
  } else {
    $object_type = $calendarInfo['object_type'];
    foreach $day (split(',', lavnn('days'))) {
      $sqlParams = array('calendar' => $id, 'day' => $day, 'month' => $month, 'year' => $year, 'comment' => $comment);
      $event_id = $objCal->create_event(%sqlParams);
      # Process special types of calendars 
      if ($object_type == 'employeeabsence') {
        $eventparams = array(
          'event_type' => 'employeeabsence', 'event_id' => $event_id, 
          'day_type' => lavnn('day_type'), 'qty' => lavnn('qty'),
        ); 
        $objCal->extend_event(%eventparams);
      }
      if ($object_type == 'office') {
        $eventparams = array(
          'event_type' => 'office', 'event_id' => $event_id, 
          'day_type' => lavnn('day_type'), 'mandatory_vacation' => lavnn('mandatory_vacation'), 'transferable' => lavnn('transferable'),
        ); 
        $objCal->extend_event(%eventparams);
      }
    }
    go("?i=$module/viewmonth&id=$id&year=$year&month=$month&nocache=$nocache");
  }
}
go("?p=$module/mycalendars")
?>
