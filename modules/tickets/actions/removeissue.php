<?php
$meeting = lavnn('meeting', $_REQUEST, '');
$ticket = lavnn('ticket', $_REQUEST, '');
if ($meeting <> '') {
  $_REQUEST['target'] = "meeting:$meeting";
  $runtime->db->sqlrun($module, 'RemoveTicketTarget', $_REQUEST) if $ticket <> '';
  go("?p=$module/viewmeeting&id=$meeting&tab=issues");
} else {
  go("?p=$module/meetings");
}

?>
