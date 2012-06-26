<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'UpdateWorkflow', $_REQUEST);
} else {
  $_REQUEST['creator'] ||= $r['userID'];
  $id = $runtime->sid($module, 'InsertWorkflow', $_REQUEST);
  go("?p=$module/edit&id=$id&tab=info");
}
go("?p=$module/list");
?>
