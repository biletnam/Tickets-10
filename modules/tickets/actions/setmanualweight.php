<?php

$ticket = lavnn('id', $_REQUEST, 0);
$weight = lavnn('weight', $_REQUEST, 0);

if ($ticket > 0 && $weight > 0) {
  srun($module, 'SetManualWeight', $_REQUEST);
  $ticketInfo = $objT->get_ticket($ticket);
  if ($ticketInfo['handler'] > 0 && $ticketInfo['status'] == 'OPN') {
    srun($module, 'RecalculateUserEstimations', array('user_id' => $ticketInfo['handler']));
  }
  print dot('ticketweight', $_REQUEST);
}

1;
?>
