<?php
$id = lavnn('id');
$runtime->db->sqlrun($module, 'PostponeReminder', $_REQUEST) if $id > 0;
$nextUrl = lavnn('nextUrl') || "?p=$module/planner"; 
go($nextUrl);
?>
