<?php

use objStaffManagement;
use objCalendar;
$objSM = new objStaffManagement($r);
$objCal = new objCalendar($r);

($day, $month, $year) = split('\.', lavnn('activedate'));
$comment = lavnn('comment') || 'automatic comment';
$processed = array();

$employees = lavnn('id'); 
foreach $employee (@employees) {
  $employeeInfo = $objSM->get_user_info($employee); 
  $sqlParams = array(
    'calendar' => $employeeInfo['absencecalendar'], 
    'day' => $day, 
    'month' => $month, 
    'year' => $year, 
    'comment' => $comment
  );
  $event_id = $objCal->create_event(%sqlParams);
  if ($event_id > 0) {
    $eventparams = array(
      'event_type' => 'employeeabsence', 
      'event_id' => $event_id, 
      'day_type' => lavnn('absence_type'), 
      'qty' => lavnn('qty'),
    ); 
    $objCal->extend_event(%eventparams);
    $events = array($event_id);
    $objSM->request_absence($employee, $events, '[automatic request from Edit Office Absence page] '.$comment);
    push @processed, $employeeInfo['strNick'];
  }
}

if (count($processed) > 0) {
  $_SESSION['flash'] = 'Absence (or overwork) requested for ' . join(', ', @processed));
}

$office = lavnn('office', $_REQUEST, 0);
if ($office <> 0) {
  go("?p=$module/editofficeabsences&office=$office");
} else {
  go("?p=$module/offices");
}

?>
