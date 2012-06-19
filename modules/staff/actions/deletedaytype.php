<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $cnt = $runtime->db->sqlrun($module, 'DeleteDayType', $_REQUEST);
  if ($cnt > 0) {
    $runtime->db->sqlrun($module, 'DeleteDayTypeOffices', $_REQUEST);
  } 
  $_SESSION['flash'] = 'Calendar day type deleted');
}

go("?p=$module/settings&tab=daytypes");

?>
