<?php
foreach $id (split(',', $_REQUEST['ids'])) {
  $params = array('id' => $id, 'text' => $_REQUEST{"event_text_$id"}, 'date' => $_REQUEST{"event_date_$id"});
  $runtime->db->sqlrun($module, 'UpdateReminder', $params);
}
go("?p=$module/dashboard&tab=planner");

?>
