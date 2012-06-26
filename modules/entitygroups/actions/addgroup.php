<?php
$_REQUEST['entity_type'] = $_REQUEST['group_type_dropdown'] || $_REQUEST['group_type_freeform'];
if ($_REQUEST['entity_type'] <> '' && $_REQUEST['name'] <> '') {
  $id = $runtime->sid($module, 'InsertEntityGroup', $_REQUEST);
  if ($id > 0) {
    $_SESSION['flash'] = 'Entity Group was successfully added');
  } else {
    $_SESSION['error'] = 'Entity Group was not added');
  }
} else {
  $_SESSION['error'] = 'Please provide both entity type (either from dropdown or freeform text box) and name!');
}
go("?p=$module/list");

?>
