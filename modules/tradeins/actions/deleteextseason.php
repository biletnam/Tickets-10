<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'DeleteExtSeason', $_REQUEST);
  set_cookie('flash', 'External Resort Season deleted');
}
go("?p=$module/extseasons");

?>
