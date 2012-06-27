<?php

$nextUrl = lavnn('nextUrl', $_REQUEST, "?p=$module/dashboard&tab=planner");

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
    $runtime->db->sqlrun($module, 'ForgetReminder', $_REQUEST);
}

$r->go($nextUrl);

?>
