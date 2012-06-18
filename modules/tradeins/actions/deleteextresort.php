<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'DeleteExtResort', $_REQUEST);
  set_cookie('flash', 'External Resort deleted');
}
go("?p=$module/extresorts");

?>
