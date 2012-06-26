<?php

$ticket_id = lavnn('ticket', $_REQUEST, '');
$comment = lavnn('comment', $_REQUEST, '');
if ($ticket_id <> '' && $comment <> '') {
  $result = $objT->add_comment($ticket_id, $_REQUEST); 
  if ($result['returncode'] == 0 && $result['ticket_history_id'] > 0) {
    $ticket_id = $result['ticket_id'];
    $_SESSION['flash'] = 'Comment added');
    go("?p=tickets/viewticket&id=$ticket_id");  
  } else {
    $_SESSION['error'] = 'Could not add comment');
    go('?p=tickets/mytickets');
  }
} else {
  $_SESSION['error'] = 'Malformed request, showing list of tickets instead');
  go('?p=tickets/mytickets');
}


?>
