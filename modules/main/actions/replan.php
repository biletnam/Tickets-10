<?php
foreach $id (split(',', $_REQUEST['ids'])) {
  $params = ('id' => $id, 'text' => $_REQUEST{"event_text_$id"}, 'date' => $_REQUEST{"event_date_$id"});
  srun($module, 'UpdateReminder', $params);
}
go("?p=$module/dashboard&tab=planner");

?>
