<?php

$id = lavnn('id');
if ($id <> '') {
  $result = $objT->delete_notification_recipient($id);
  if ($result['returncode'] == 0 && $result['ticket_history_id'] > 0) {
    $ticket_id = $result['ticket_id'];
    set_cookie('flash', 'Person excluded from notification list');
    go("?p=tickets/viewticket&id=$ticket_id&tab=notified");
  } else {
    set_cookie('error', 'Invalid notification requested');
  }
} else {
  set_cookie('error', 'Please specify a person to exclude from notification list');
}
    
go('?p=tickets/mytickets');

?>
