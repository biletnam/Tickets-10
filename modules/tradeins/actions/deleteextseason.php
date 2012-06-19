<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'DeleteExtSeason', $_REQUEST);
  $_SESSION['flash'] = 'External Resort Season deleted');
}
go("?p=$module/extseasons");

?>
