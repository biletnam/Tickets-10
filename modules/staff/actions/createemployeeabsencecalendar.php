<?php
use objEmployee;

$id = lavnn('id', $_REQUEST, 0);
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
  go("?p=$module/employee&id=$id&tab=problemreport");
} else {
  go("?p=$module/offices");
}

?>
