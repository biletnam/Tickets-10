<?php
use CFileUploader;
$fu = new CFileUploader($r);

$client_id = lavnn('client', $_REQUEST, 0);
$editor = $r['userID'] || 0;
if ($client_id > 0) {
  $fileid = $fu->uploadfile("attachment");
  if ($fileid > 0) {
    $id = sid($module, 'AddClientAttachment', array('client' => $client_id, 'fileid' => $fileid, 'editor' => $editor)); 
    if ($id > 0) {
      set_cookie('flash', 'Attachment added');
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
if ($client_id > 0) {
  go("?p=$module/viewclient&id=$client_id&tab=attachments");
} else {
  go("?p=$module/search");
}


?>
