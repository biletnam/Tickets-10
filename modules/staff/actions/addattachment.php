<?php
use CFileUploader;
$fu = new CFileUploader($r);

use objStaffManagement;
$objSM = new objStaffManagement($r);

$employee = lavnn('employee', $_REQUEST, 0);
$editor = $r['userID'] || 0;
if ($employee > 0) {
  $fileid = $fu->uploadfile("attachment");
  if ($fileid > 0) {
    $id = $objSM->add_employee_attachment(('employee' => $employee, 'fileid' => $fileid, 'editor' => $editor)); 
    if ($id > 0) {
      $_SESSION['flash'] = 'Attachment added');
      # Also, set metada if provided
      if (dot('addattachment.conditions', $_REQUEST) <> '') {
        $_REQUEST['id'] = $id;
        $fu->save_metadata(%_REQUEST);
      }
    } else {
      set_cookie('error', "Could not add attachment $fileid");
    }
  } else {
    set_cookie('error', 'Could not upload file');
  }
}
if ($employee > 0) {
  go("?p=$module/employee&id=$employee&tab=attachments");
} else {
  go("?p=$module/offices");
}


?>
