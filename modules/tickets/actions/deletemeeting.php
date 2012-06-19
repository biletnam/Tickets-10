<?php
$id = lavnn('id');
if ($id > 0) {
  $runtime->db->sqlrun($module, 'DeleteMeeting', array('id' => $id));
}
go("?p=$module/meetings");

?>
