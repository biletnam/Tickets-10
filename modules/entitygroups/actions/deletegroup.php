<?php
$id = lavnn('id');
if ($id > 0) {
  $runtime->db->sqlrun($module, 'DeleteEntityGroup', $_REQUEST);
}
go("?p=$module/list");

?>
