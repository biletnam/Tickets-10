<?php
$_REQUEST['entity_type'] = $_REQUEST['group_type_dropdown'] || $_REQUEST['group_type_freeform'];
if ($_REQUEST['entity_type'] <> '' && $_REQUEST['name'] <> '') {
  $id = sid($module, 'InsertEntityGroup', $_REQUEST);
  if ($id > 0) {
    set_cookie('flash', 'Entity Group was successfully added');
  } else {
    set_cookie('error', 'Entity Group was not added');
  }
} else {
  set_cookie('error', 'Please provide both entity type (either from dropdown or freeform text box) and name!');
}
go("?p=$module/list");

?>
