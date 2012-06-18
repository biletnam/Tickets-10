<?php

use CFileUploader;
$fu = new CFileUploader($r);
use objStaffManagement;
$objSM = new objStaffManagement($r);

$employee = lavnn('id', $_REQUEST, 0);
$editor = $r['userID'] || 0;
if ($employee > 0) {
  $fileid = $fu->uploadfile("avatar");
  if ($fileid > 0) {
    $attachmentid = $objSM->add_employee_attachment((
      'employee' => $employee, 
      'fileid' => $fileid, 
      'editor' => $editor, 
      'description' => 'Avatar',
    ));
    if ($attachmentid > 0) {
      $checkid = $objSM->set_employee_avatar((
        'employee' => $employee,
        'attachmentid' => $attachmentid,
      ));
      if ($checkid == $attachmentid) {
        set_cookie('flash', 'Avatar set');
      } else {
        set_cookie('error', 'Could not set avatar');
      }
    } else {
      set_cookie('error', 'Could not upload an attachment!');
    }
  } else {
    set_cookie('error', 'Could not upload file');
  }
}
if ($employee > 0) {
  go("?p=$module/employee&id=$employee&tab=personal");
} else {
  go("?p=$module/offices");
}


?>
