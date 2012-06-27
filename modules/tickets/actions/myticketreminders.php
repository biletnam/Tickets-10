<?php
$delete = lavnn('delete', $_REQUEST, 0);
if ($delete > 0) {
  $runtime->db->sqlrun($module, 'DeleteTicketReminder', array('id' => $delete));
}

$_REQUEST['user_id'] = $r['userID'];
$ticketInfo = $runtime->s2r($module, 'GetTicketInfo', $_REQUEST);
if (count($ticketInfo) > 0) {
  $reminders = $runtime->s2a($module, 'ListTicketReminders', array('user_id' => $r['userID'], 'ticket_id' => lavnn('id')));
  $ticketInfo['remindershistory'] = $reminders;
  print $r->txt->do_template($module, 'viewticket.action.reminders', $ticketInfo);
}
?>
