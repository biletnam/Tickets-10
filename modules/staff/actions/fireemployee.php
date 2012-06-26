<?php
$id = lavnn('id', $_REQUEST, 0);
$nextUrl = '?p=staff/offices';
if ($id > 0) {
  $nextUrl = "?p=staff/employee&id=$id&tab=special";
  use objStaffManagement;
  $objSM = new objStaffManagement($r);
  if (lavnn('fired_since') <> '' && lavnn('fired_reason') <> '') {
    if (0 < $objSM->fire_employee($_REQUEST)) {
      $_SESSION['flash'] = 'Employee marked as fired. Do not forget about manual tasks related to firing procedure!');
      $nextUrl = "?p=staff/employee&id=$id&tab=fired";
    } else {
      $_SESSION['error'] = 'Could not fire employee');
    }
  } else {
    $_SESSION['error'] = 'Please specify date and reason of firing');
  }
}
gi($nextUrl);
?>
