<?php

use objCalendar;
$objCal = new objCalendar($r);

$office = lavnn('office', $_REQUEST, 0);
$id = lavnn('id', $_REQUEST, 0);
$calendarInfo = array();
if ($id > 0) {
  %calendarInfo = $objCal->get_office_calendar(('id' => $id)); 
  $office = $calendarInfo['office_id'];
  $calendarInfo['pagetitle'] = $r->txt->do_template($module, 'title.officecalendar.edit', $calendarInfo);  
  $paidabsencetypes = $runtime->s2a($module, 'ListOfficeCalendarAbsenceTypes', array('office' => $office, 'id' => $id));
  $calendarInfo['paidabsencetypes'] = $paidabsencetypes;
} else {
  $calendarInfo['office_id'] = $office;
  $officeInfo = $runtime->s2r($module, 'GetOfficeDetails', array('office' => $office));
  $calendarInfo['pagetitle'] = $r->txt->do_template($module, 'title.officecalendar.new', $officeInfo);
}
if ($acc->is_superadmin() && $calendarInfo['cnt_employees'] == 0) {
  $offices = $runtime->s2a($module, 'ListOffices'); 
  $calendarInfo['officeoptions'] = arr2ref(genOptions($offices, 'lngId', 'strName'));
  $calendarInfo['move2office'] = $r->txt->do_template($module, 'officecalendar.move2office', $calendarInfo);
}
$page->add('title',  $calendarInfo['pagetitle'];
$page->add('main', $r->txt->do_template($module, 'officecalendar', $calendarInfo);


?>
