<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'UpdateExtResort', $_REQUEST);
} else {
  $runtime->db->sqlrun($module, 'InsertExtResort', $_REQUEST);
}
go("?p=$module/extresorts");

?>
