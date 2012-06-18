<?php
use objEmployee;

  $nocache = sprintf("%f", time());
$id = lavnn('id') || $r['userID'];
if ($id > 0) {
  $objEmp = new objEmployee($r);
  $calendar_id = $objEmp->get_absence_calendar(('employee' => $id));
  if ($calendar_id > 0) {
    set_cookie('error', 'Absence Calendar for the user already exists');
  } else {
    if ($objEmp->create_absence_calendar(('employee' => $id)) > 0) {
      set_cookie('flash', 'Absence Calendar created for the user');
    } else {
      set_cookie('error', 'Absence Calendar for the user was not created');
    }  
  }
  go("?p=$module/view&special=employeeabsence&employee_id=$id&tab=month&nocache=$nocache");
} else {
  go("?p=$module/mycalendars");
}

?>
