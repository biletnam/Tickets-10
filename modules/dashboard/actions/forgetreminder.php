<?php
$id = lavnn('id');
srun($module, 'ForgetReminder', $_REQUEST) if $id > 0;
$nextUrl = lavnn('nextUrl') || "?p=$module/planner"; 
go($nextUrl);
?>
