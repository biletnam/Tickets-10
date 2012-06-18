<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'UpdateExtResort', $_REQUEST);
} else {
  srun($module, 'InsertExtResort', $_REQUEST);
}
go("?p=$module/extresorts");

?>
