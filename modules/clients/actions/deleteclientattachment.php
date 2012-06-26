<?php

use CFileUploader;
$fu = new CFileUploader($r);

$client = 0;

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $attachmentInfo = $fu->get_attachment($id);
  $client = $attachmentInfo['entity_id'];
  if ($client > 0 && $attachmentInfo['entity_type'] == 'client') {
    $fu->delete_attachment($id);
    $_SESSION['flash'] = 'Attachment deleted');
  } else {
    $_SESSION['error'] = 'Could not delete attachment');
  }
}
if ($client > 0) {
  go("?p=$module/viewclient&id=$client&tab=attachments");
} else {
  go("?p=$module/offices");
}


?>
