<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  set_cookie('flash', 'Table updated') if srun($module, 'UpdateEditableTable', $_REQUEST) > 0; 
} else {
#  formdebug($_REQUEST); die(spreview($module, 'InsertEditableTable', $_REQUEST));
  $id = sid($module, 'InsertEditableTable', $_REQUEST);
  set_cookie('flash', 'Table inserted') if $id > 0; 
}
if ($id > 0) {
  go("?p=$module/editable&id=$id");
} else {
  go("?p=$module/editables");
}
?>
