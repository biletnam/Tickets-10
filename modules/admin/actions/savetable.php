<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $_SESSION['flash'] = 'Table updated') if $runtime->db->sqlrun($module, 'UpdateEditableTable', $_REQUEST) > 0; 
} else {
#  formdebug($_REQUEST); die(spreview($module, 'InsertEditableTable', $_REQUEST));
  $id = $runtime->sid($module, 'InsertEditableTable', $_REQUEST);
  $_SESSION['flash'] = 'Table inserted') if $id > 0; 
}
if ($id > 0) {
  go("?p=$module/editable&id=$id");
} else {
  go("?p=$module/editables");
}
?>
