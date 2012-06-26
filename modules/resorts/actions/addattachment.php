<?php
use CFileUploader;
$fu = new CFileUploader($r);

$hotel = lavnn('hotel', $_REQUEST, 0);
$editor = $r['userID'] || 0;
if ($hotel > 0) {
  $fileid = $fu->uploadfile("attachment");
  if ($fileid > 0) {
    $id = $runtime->sid($module, 'AddAttachment', array('hotel' => $hotel, 'fileid' => $fileid, 'editor' => $editor)); 
    if ($id > 0) {
      $_SESSION['flash'] = 'Attachment added');
      # Also, set metada if provided
      if (dot('addattachment.conditions', $_REQUEST) <> '') {
        $_REQUEST['id'] = $id;
        $fu->save_metadata($_REQUEST);
      }

      $hotelInfo = $runtime->s2r($module, 'GetHotelInfo', array('id' => $hotel));
      $attachmentInfo = $runtime->s2r('main', 'GetAttachmentInfo', array('id' => $id));
      # Distribute notification about new attachment
      use objNotification;
      $objN = new objNotification($r);
      $subject = $runtime->txt->do_template($module, 'notification.attachment.added.subject', $hotelInfo);
      $body = $runtime->txt->do_template($module, 'notification.attachment.added.body', $attachmentInfo);
      $nid = $objN->add_notification('hotel', $hotel, $subject, $body);
      $users = $acc->list_users_for_resource('followhotel', $hotel);
      foreach $employee (@users) {
        $objN->add_notification_recipient($nid, $employee['lngId']);
      }        

    } else {
      $_SESSION['error'] = "Could not add attachment $fileid");
    }
  } else {
    $_SESSION['error'] = 'Could not upload file');
  }
}
if ($hotel > 0) {
  go("?p=$module/edithotel&id=$hotel&tab=attachments");
} else {
  go("?p=$module/hotels");
}


?>
