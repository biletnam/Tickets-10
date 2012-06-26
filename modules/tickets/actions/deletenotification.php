<?php

$id = lavnn('id');
if ($id <> '') {
  $result = $objT->delete_notification_recipient($id);
  if ($result['returncode'] == 0 && $result['ticket_history_id'] > 0) {
    $ticket_id = $result['ticket_id'];
    $_SESSION['flash'] = 'Person excluded from notification list');
    go("?p=tickets/viewticket&id=$ticket_id&tab=notified");
  } else {
    $_SESSION['error'] = 'Invalid notification requested');
  }
} else {
  $_SESSION['error'] = 'Please specify a person to exclude from notification list');
}
    
go('?p=tickets/mytickets');

?>
