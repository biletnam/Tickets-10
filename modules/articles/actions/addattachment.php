<?php

use CFileUploader;
$fu = new CFileUploader($r);

$article = param('article', $_REQUEST, 0);
$editor = $r['userID'] || 0;

if ($article > 0) {
  $fileid = $fu->uploadfile("attachment", $_REQUEST, 0); 
  if ($fileid > 0) {
    $id = sid($module, 'AddAttachment', array('article' => $article, 'fileid' => $fileid, 'editor' => $editor)); 
    if ($id > 0) {
      $_SESSION['flash'] = 'Attachment added');
      # Also, set metada if provided
      if (dot('addattachment.conditions', $_REQUEST) <> '') {
        $_REQUEST['id'] = $id;
        $fu->save_metadata(%_REQUEST);
      }

      $articleInfo = $objA->get_article(('id' => $article));
      $attachmentInfo = $runtime->s2r('main', 'GetAttachmentInfo', array('id' => $id));
      # TODO Distribute notification about new attachment?

    } else {
      set_cookie('error', "Could not add attachment $fileid");
    }
  } else {
    set_cookie('error', 'Could not upload file');
  }
}
if ($article > 0) {
  go("?p=$module/edit&id=$article&tab=attachments");
} else {
  go("?p=$module/home");
}

?>
