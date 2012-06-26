<?php

$ticket_id = lavnn('ticket', $_REQUEST, '');
if ($ticket_id <> '') {
  $result = $objT->add_notification_recipient($ticket_id, $_REQUEST);
  if ($result['returncode'] == 0 && $result['ticket_history_id'] > 0) {
    $_SESSION['flash'] = 'Person added to notification list');
  } else {
    $error_description = $result['error_description'];
    $_SESSION['error'] = "Could not add person to notification list ($error_description)");
  }
  go("?p=tickets/viewticket&id=$ticket_id&tab=notified");
} else {
  $_SESSION['error'] = 'Please specify correct ticket for editing its notification list!');
}

go('?p=tickets/mytickets');

?>
