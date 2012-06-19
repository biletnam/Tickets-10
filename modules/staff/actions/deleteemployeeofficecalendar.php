<?php

$id = lavnn("id", $_REQUEST, 0);
$employee = 0;
if ($id > 0) {
  $employeeOfficeCalendarInfo = $runtime->s2r($module, 'GetEmployeeOfficeCalendarInfo', $_REQUEST);
  $employee = $employeeOfficeCalendarInfo['employee'];
  if (count($employeeOfficeCalendarInfo) > 0) {
    $runtime->db->sqlrun($module, 'DeleteEmployeeOfficeCalendar', $_REQUEST);
    $_SESSION['flash'] = 'Office Calendar removed from employee');
  }
}

if ($employee > 0) {
  go("?p=$module/employee&id=$employee&tab=calendars");
} else {
  go("?p=$M/offices");
}
?>
