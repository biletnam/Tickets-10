<?php
$id = lavnn('id');
if ($id > 0) {
  $runtime->db->sqlrun($module, 'UpdateMeeting', $_REQUEST);
  go("?p=$module/viewmeeting&id=$id&tab=details");
} else {
  go("?p=$module/meetings");
}

?>
