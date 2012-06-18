<?php
$id = lavnn('id', $_REQUEST, 0);
$nextUrl = '?p=staff/offices';
if ($id > 0) {
  $nextUrl = "?p=staff/employee&id=$id&tab=special";
  use objStaffManagement;
  $objSM = new objStaffManagement($r);
  if (lavnn('fired_since') <> '' && lavnn('fired_reason') <> '') {
    if (0 < $objSM->fire_employee(%_REQUEST)) {
      set_cookie('flash', 'Employee marked as fired. Do not forget about manual tasks related to firing procedure!');
      $nextUrl = "?p=staff/employee&id=$id&tab=fired";
    } else {
      set_cookie('error', 'Could not fire employee');
    }
  } else {
    set_cookie('error', 'Please specify date and reason of firing');
  }
}
gi($nextUrl);
?>
