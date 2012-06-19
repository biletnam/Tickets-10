<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'DeleteExtResort', $_REQUEST);
  $_SESSION['flash'] = 'External Resort deleted');
}
go("?p=$module/extresorts");

?>
