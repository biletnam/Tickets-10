<?php
$employee = lavnn("employee", $_REQUEST, 0);
$id = lavnn("id", $_REQUEST, 0);

use objStaffManagement;
$objSM = new objStaffManagement($r);

$absencedays = array(); 
while (($key, $value) = each $_REQUEST) {
  ($prefix, $suffix) = split('_', $key, 2);
  if ($prefix == 'days' && $suffix <> '') {
    push @absencedays, array('day_type' => $suffix, 'days_cnt' => $value));
  }
}
$_REQUEST['absencedays'] = $absencedays;

$result = $objSM->assign_calendar($_REQUEST);
if ($result > 0) {
  if ($id > 0) {
    $_SESSION['flash'] = 'Information about assigned calendars changed');
  } else {
    $_SESSION['flash'] = 'Office calendar asigned to employee');
  }
} else {
  $_SESSION['error'] = 'Could not assign office calendar to employee');
}

if ($employee > 0) {
  go("?p=$module/employee&id=$employee&tab=calendars");
} else {
  go("?p=$M/offices");
}

?>
