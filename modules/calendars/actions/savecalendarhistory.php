<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  use objStaffManagement;
  $objSM = new objStaffManagement($r);
  $result = $objSM->save_user_specialdata($_REQUEST);
  if ($result['returncode'] == 1) {
    $_SESSION['flash'] = 'Employee calendar history changed');
  } else {
    $_SESSION['error'] = 'Employee calendar history could not be changed');
  }
  $nocache = sprintf("%f", time());
  go("?p=$module/employee&id=$id&nocache=$nocache&tab=calendarlist");
}
go('?p=$module/search');

?>
