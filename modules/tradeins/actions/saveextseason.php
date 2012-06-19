<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'UpdateExtSeason', $_REQUEST);
} else {
  $runtime->db->sqlrun($module, 'InsertExtSeason', $_REQUEST);
}
go("?p=$module/extseasons");

?>
