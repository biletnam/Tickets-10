<?php
$ticket_id = lavnn('ticket_id', $_REQUEST, 0);
$_REQUEST['user_id'] = $r['userID'];
$id = $runtime->sid($module, 'InsertTicketFolder', $_REQUEST);
if ($id > 0) {
  $autoadd = lavnn('autoadd', $_REQUEST, '');
  if ($autoadd <> '') {
    $_REQUEST['folder_id'] = $id;
    $id = $runtime->sid($module, 'InsertTicketFolderMapping', $_REQUEST);
    if ($id > 0) {
      $_SESSION['flash'] = 'Folder added, and ticket is automatically added to it');
    } else {
      $_SESSION['flash'] = 'Folder added, but ticket is not automatically added to it');
    }
  } else {
    $_SESSION['flash'] = 'Folder added');
  }
} else {
  $_SESSION['error'] = 'Could not add folder');
}

if ($ticket_id > 0) {
  go("?p=tickets/viewticket&id=$ticket_id&tab=folders");
} else {
  go("?p=tickets/mytickets");
}
?>
