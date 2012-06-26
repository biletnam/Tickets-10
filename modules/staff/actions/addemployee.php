<?php

use objStaffManagement;
$objSM = new objStaffManagement($r);

$newid = $objSM->add_employee($_REQUEST);
if ($newid > 0) {
  $_SESSION['flash'] = 'User added');
  go("?p=$module/employee&id=$newid");
} else {
  $_SESSION['error'] = 'Could not add user');
  go("?p=$module/offices");
} 


?>
