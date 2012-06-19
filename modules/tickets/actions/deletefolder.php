<?php
$id = lavnn('id');
if ($id > 0) {
  $runtime->db->sqlrun($module, 'DeleteFolder', $_REQUEST);
}
$nextUrl = lavnn('nextUrl') == '' ? "?p=$module/mytickets" : '?'.lavnn('nextUrl').'&tab=folders';
go($nextUrl);

?>
