<?php
$id = lavnn('id');
srun($module, 'PostponeReminder', $_REQUEST) if $id > 0;
$nextUrl = lavnn('nextUrl') || "?p=$module/dashboard&tab=planner"; 
go($nextUrl);
?>
