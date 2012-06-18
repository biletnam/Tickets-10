<?php
$employee = lavnn('employee', $_REQUEST, 0); 
if ($employee > 0) {
  use objStaffManagement;
  $objSM = new objStaffManagement($r);
  $employeeInfo = $objSM->get_user_info($employee);
  if (count($employeeInfo) > 0) {
    $avatar = $employeeInfo['avatar'] || 0;
    if ($avatar > 0) {
      use CFileUploader;
      $objFU = new CFileUploader($r);
      $attachmentInfo = $objFU->get_attachment($avatar);
      $filename = $attachmentInfo['filename_safe'] || '';
      if ($filename <> '') {
        $baseurl = 'http://' . $ENV['HTTP_HOST'] . $r['config']['URLPICTURES'];
        go2('', $baseurl.'/'.$attachmentInfo['fileid'].'.'.$filename); exit();
      }
    } else {
      $gender = $employeeInfo['lngSex'] || '';
      if ($gender == '') {
        go2('', $r['config']['BASEURL_IMAGES'].'/avatar.employee.jpg'); exit();
      } else {
        go2('', $r['config']['BASEURL_IMAGES']."/avatar.employee.$gender.jpg"); exit();
      }
    }
  }
} elseif ($employee == -1) {
  go2('', $r['config']['BASEURL_IMAGES'].'/avatar.anonymous.jpg'); exit();
}

go2('', $r['config']['BASEURL_IMAGES'].'/blank.jpg'); exit();

?>
