<?php
$id = lavnn('id');
if ($id > 0) {
  srun($module, 'DeleteEntityGroup', $_REQUEST);
}
go("?p=$module/list");

?>
