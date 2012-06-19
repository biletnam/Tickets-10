<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'DeleteDocType', $_REQUEST);
}
go("?p=$module/doctypes");
?>
