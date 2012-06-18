<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'UpdateDocType', $_REQUEST);
} else {
  $id = sid($module, 'InsertDocType', $_REQUEST);
}
if ($id > 0) {
  go("?p=$module/editdoctype&id=$id&tab=viewers");
} else {
  go("?p=$module/doctypes");
}

?>
