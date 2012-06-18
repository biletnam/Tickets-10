<?php

use CFileUploader;
$fu = new CFileUploader($r);
use objStaffManagement;
$objSM = new objStaffManagement($r);
$employee = 0;

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $attachmentInfo = $fu->get_attachment($id);
  $employee = $attachmentInfo['entity_id'];
  if ($employee > 0) {
    $employeeInfo = $objSM->get_user_info($employee);
    $avatar = $employeeInfo['avatar'] || 0;
    if ($avatar == $id) { # Also, reset employee avatar
      $objSM->set_employee_avatar(('employee' => $employee));
    }
    $fu->delete_attachment($id);
    set_cookie('flash', 'Attachment deleted');
  } else {
    set_cookie('error', 'Could not delete attachment');
  }
}
if ($employee > 0) {
  go("?p=$module/employee&id=$employee&tab=attachments");
} else {
  go("?p=$module/offices");
}


?>
