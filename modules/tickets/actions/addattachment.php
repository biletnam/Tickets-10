<?php

use CFileUploader;
$fu = new CFileUploader($r);

$ticket = lavnn('ticket', $_REQUEST, '');
$editor = $_REQUEST['editor'] = array($r['userID'] || 0);
if ($ticket <> '') {
  $fileid = $fu->uploadfile("attachment");
  if ($fileid > 0) {
    $_REQUEST['fileid'] = $fileid;
    $result = $objT->add_attachment($ticket, $_REQUEST); 
    if ($result['returncode'] == 0 && $result['ticket_history_id'] > 0) {
      $_SESSION['flash'] = 'Attachment added');
    } else {
      $_SESSION['error'] = 'Could not add attachment');
    }
  } else {
    $_SESSION['error'] = 'Could not upload file');
  }
  go("?p=tickets/viewticket&id=$ticket&tab=attachments");  
} else {
  $_SESSION['error'] = 'Malformed request, showing list of tickets instead');
}
go('?p=tickets/mytickets');

?>
