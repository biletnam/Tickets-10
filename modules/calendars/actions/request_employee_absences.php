<?php

$ids = lavnn('ids');
$employee = 0;
 
if (count($ids) > 0) {

  use objCalendar;
  use objTicketing;
  use objStaffManagement;
  use objEmployee;
  $objCal = new objCalendar($r);
  $objSM = new objStaffManagement($r);

  # Get calendar info, related employee and his line manager and HR staff in employee's office
  $calendarInfo = $objCal->get_calendar_info(('id' => $_REQUEST['yearview_calendar_id']));
  $employee = $calendarInfo['object_id'];
  $employeeInfo = $objSM->get_user_info($employee); 
  $employeeSpecialData = $objSM->get_user_specialdata($employee);
  $linemanager = $employeeSpecialData['line_manager'] || 0;
  if ($linemanager == 0) {
    set_cookie('error', 'Absence request cannot be sent because Line Manager is not defined');
  } else {
    $objSM->request_absence($employee, $ids, lavnn('requestabsence_comment'));
  }
  
} else {
  set_cookie('error', 'Please select some items in order to send absence request')
}

if ($employee > 0 && $employee <> $r['userID']) { # request was sent on employee's behalf by HR staff
  go("?p=$module/view&special=employeeabsence&employee_id=$employee&tab=year");
} else { # request was sent by employee himself
  go("?p=$module/view&special=employeeabsence&tab=year");
}

?>
