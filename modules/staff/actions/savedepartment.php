<?php

$id = $_REQUEST['id'] || 0;

if ($id != 0) {
  srun($module, 'UpdateDepartment', $_REQUEST);
  set_cookie('flash', 'Department data updated');
}
go("?p=$module/department&id=$id");

?>
