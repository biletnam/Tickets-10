<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'DeleteDocType', $_REQUEST);
}
go("?p=$module/doctypes");
?>
