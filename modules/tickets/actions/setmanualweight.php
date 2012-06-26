<?php

$ticket = lavnn('id', $_REQUEST, 0);
$weight = lavnn('weight', $_REQUEST, 0);

if ($ticket > 0 && $weight > 0) {
  $runtime->db->sqlrun($module, 'SetManualWeight', $_REQUEST);
  $ticketInfo = $objT->get_ticket($ticket);
  if ($ticketInfo['handler'] > 0 && $ticketInfo['status'] == 'OPN') {
    $runtime->db->sqlrun($module, 'RecalculateUserEstimations', array('user_id' => $ticketInfo['handler']));
  }
  print $runtime->txt->do_template($module, 'ticketweight', $_REQUEST);
}

1;
?>
