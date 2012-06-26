<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'UpdateDocType', $_REQUEST);
} else {
  $id = $runtime->sid($module, 'InsertDocType', $_REQUEST);
}
if ($id > 0) {
  go("?p=$module/editdoctype&id=$id&tab=viewers");
} else {
  go("?p=$module/doctypes");
}

?>
