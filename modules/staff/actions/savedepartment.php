<?php

$id = $_REQUEST['id'] || 0;

if ($id != 0) {
  $runtime->db->sqlrun($module, 'UpdateDepartment', $_REQUEST);
  $_SESSION['flash'] = 'Department data updated');
}
go("?p=$module/department&id=$id");

?>
