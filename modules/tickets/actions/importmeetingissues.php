<?php
$id = lavnn('meeting');
if ($id > 0) {
  $copyfrom = lavnn('copyfrom', $_REQUEST, '');
  $runtime->db->sqlrun($module, 'CopyMeetingIssues', array('copyfrom' => $copyfrom, 'id' => $id)) if $copyfrom <> '';
  $tickets = lavnn('tickets', $_REQUEST, '');
  $runtime->db->sqlrun($module, 'AddMeetingTickets', array('tickets' => $tickets, 'id' => $id)) if $tickets <> '';
  go("?p=$module/viewmeeting&id=$id&tab=issues");
} else {
  $runtime->$_SESSION['error'] = 'Could not create meeting!');
  go("?p=$module/newmeeting");
}

?>
