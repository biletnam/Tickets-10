<?php
$id = lavnn('id');
if ($id > 0) {
  srun($module, 'DeleteMeeting', array('id' => $id));
}
go("?p=$module/meetings");

?>
