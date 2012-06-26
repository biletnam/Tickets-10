<?php

use CFileUploader;
$fu = new CFileUploader($r);
$hotel = 0;

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $attachmentInfo = $fu->get_attachment($id);
  $hotel = $attachmentInfo['entity_id'];
  if ($hotel > 0) {
    $hotelInfo = $runtime->s2r($module, 'GetHotelInfo', $_REQUEST);
    $fu->delete_attachment($id);
    $_SESSION['flash'] = 'Attachment deleted');
  } else {
    $_SESSION['error'] = 'Could not delete attachment');
  }
}
if ($hotel > 0) {
  go("?p=$module/edithotel&id=$hotel&tab=attachments");
} else {
  go("?p=$module/hotels");
}


?>
