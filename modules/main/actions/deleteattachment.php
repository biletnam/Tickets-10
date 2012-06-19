<?php
#$runtime->formdebug($_REQUEST); 
use CFileUploader;
$fu = new CFileUploader($r);

use objEntity;
$objE = new objEntity($r);

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $attachmentInfo = $fu->get_attachment($id);
#  $runtime->formdebug($attachmentInfo); die();
  if (count($attachmentInfo) > 0) {
    $entity_type = $attachmentInfo['entity_type'];
    $entity_id = $attachmentInfo['entity_id'];
    $fu->delete_attachment($id);
    $runtime->$_SESSION['flash'] = 'Attachment deleted');
    if ($entity_type <> '' && $entity_id > 0) {
      $url = $objE->getUrl((
        'entity_type' => $entity_type,
        'entity_id' => $entity_id,
        'extra' => 'tab=attachments'
      ));
      go($url);
    }
  } else {
    set_cookie('error', 'Could not delete attachment');
  }
}
go('');

?>
