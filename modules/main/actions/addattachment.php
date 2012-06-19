<?php

use CFileUploader;
$fu = new CFileUploader($r);

use objEntity;
$objE = new objEntity($r);

$etype = lavnn('etype', $_REQUEST, 0);
$eid = lavnn('eid', $_REQUEST, 0); # eid can be positive and negative for some entities!
$editor = $r['userID'] || 0;
if ($etype <> '' && $eid <> 0) {
  $fileid = $fu->uploadfile("attachment");
  if ($fileid > 0) {
    $id = sid($module, 'AddAttachment', array(
      'etype' => $etype,
      'eid' => $eid, 
      'fileid' => $fileid, 
      'editor' => $editor
    )); 
    if ($id > 0) {
      $_SESSION['flash'] = 'Attachment added');
      # Also, set metada if provided
      if (dot('addattachment.conditions', $_REQUEST) <> '') {
        $_REQUEST['id'] = $id;
        $fu->save_metadata(%_REQUEST);
      }
      # send automatic notification to subscribers
      $objE->notify_new_attachment((
        'entity_type' => $etype,
        'entity_id' => $eid,
        'attachment' => $id 
      ));
      # go back to the page that listed the attachments
      $url = $objE->getUrl((
        'entity_type' => $etype,
        'entity_id' => $eid,
        'extra' => 'tab=attachments'
      ));
      go($url);
    } else {
      set_cookie('error', "Could not add attachment $fileid");
    }
  } else {
    set_cookie('error', 'Could not upload file');
  }
}

go('');


?>
