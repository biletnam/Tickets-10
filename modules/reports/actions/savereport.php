<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id == 0) {
  $id = $runtime->sid($module, 'InsertPredefinedReport', $_REQUEST);
} else {
  $runtime->db->sqlrun($module, 'UpdatePredefinedReport', $_REQUEST);
}
if ($id > 0) {
  go("?p=$module/editreport&id=$id");
} else {
  go("?p=$module/editgallery");
}

?>
