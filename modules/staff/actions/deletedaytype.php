<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $cnt = srun($module, 'DeleteDayType', $_REQUEST);
  if ($cnt > 0) {
    srun($module, 'DeleteDayTypeOffices', $_REQUEST);
  } 
  set_cookie('flash', 'Calendar day type deleted');
}

go("?p=$module/settings&tab=daytypes");

?>
