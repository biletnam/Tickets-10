<?php
$id = lavnn('id');
$runtime->db->sqlrun($module, 'ForgetReminder', $_REQUEST) if $id > 0;
$nextUrl = lavnn('nextUrl') || "?p=$module/dashboard&tab=planner"; 
go($nextUrl);
?>
