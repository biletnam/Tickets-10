<?php
$delete = lavnn('delete', $_REQUEST, 0);
if ($delete > 0) {
  $runtime->db->sqlrun($module, 'DeleteTicketReminder', array('id' => $delete));
}

$_REQUEST['user_id'] = $r['userID'];
$ticketInfo = $runtime->s2r($module, 'GetTicketInfo', $_REQUEST);
if (count($ticketInfo) > 0) {
  $ticketInfo['allreminders'] = arr2ref(s2a($module, 'ListTicketReminders', array('ticket_id' => lavnn('id'))));
  print $r->txt->do_template($module, 'viewticket.reminders', $ticketInfo);
}
?>
