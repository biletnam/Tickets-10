<?php

use objEmployee;
$objEmp = new objEmployee($r);
$newid = $objEmp->add_team(%_REQUEST);
$employee = lavnn('employee', $_REQUEST, 0);

if ($newid > 0) {
  $_SESSION['flash'] = 'User team added');
} else {
  set_cookie('error', 'Could not add team to user');
} 
if ($employee > 0) {
  go("?p=$module/employee&id=$employee&tab=teams");
} else {
  go("?p=$module/offices");
}

?>
