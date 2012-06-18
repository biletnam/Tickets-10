<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'UpdateExtSeason', $_REQUEST);
} else {
  srun($module, 'InsertExtSeason', $_REQUEST);
}
go("?p=$module/extseasons");

?>
