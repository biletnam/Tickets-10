<?php
use objEmployee;
$objEmp = new objEmployee($r);

$id = lavnn('id', $_REQUEST, 0);
$employee = lavnn('employee', $_REQUEST, '');
$office_id = 0; 
$employeeOfficeCalendarInfo = array();  
$officecalendars = array();
if ($id > 0) {
  %employeeOfficeCalendarInfo = $runtime->s2r($module, 'GetEmployeeOfficeCalendarInfo', $_REQUEST); 
  $office_id = $employeeOfficeCalendarInfo['office_id'];
  $employeeOfficeCalendarInfo['pagetitle'] = $runtime->doTemplate($module, 'title.employeeofficecalendar.edit', $employeeOfficeCalendarInfo);
  
  $paidabsencetypes = $runtime->s2a($module, 'ListEmployeeOfficeCalendarDays', array(
    'office' => $office_id, 
    'employee' => $employeeOfficeCalendarInfo['employee'],
    'officecalendar' => $employeeOfficeCalendarInfo['officecalendar']
  ));
  $employeeOfficeCalendarInfo['paidabsencetypes'] = $paidabsencetypes;
  
} else {
  $employeeOfficeCalendarInfo['employee'] = $employee;
  $employeeInfo = $runtime->s2r($module, 'GetEmployeeDetails', array('id' => $employee));
  $office_id = $employeeInfo['lngWorkPlace'];
  $employeeOfficeCalendarInfo['strFirstName'] = $employeeInfo['strFirstName'];
  $employeeOfficeCalendarInfo['strLastName'] = $employeeInfo['strLastName'];
  $employeeOfficeCalendarInfo['strNick'] = $employeeInfo['strNick'];
  $employeeOfficeCalendarInfo['pagetitle'] = $runtime->doTemplate($module, 'title.employeeofficecalendar.new', $employeeInfo);
}
if ($office_id <> 0) {
  @officecalendars = $runtime->s2a($module, 'ListOfficeCalendars', array('office' => $office_id));
  $employeeOfficeCalendarInfo['officecalendaroptions'] = arr2ref(genOptions($officecalendars, 'id', 'info', $employeeOfficeCalendarInfo['officecalendar']));
}
#print Dumper($employeeOfficeCalendarInfo);
$page->add('title',  $employeeOfficeCalendarInfo['pagetitle'];
$page->add('main', $runtime->doTemplate($module, 'employeeofficecalendar', $employeeOfficeCalendarInfo);


?>
