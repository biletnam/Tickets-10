<?php
$id = lavnn('id', $_REQUEST, 0);
use objEmployee;
$objEmp = new objEmployee($r);

if ($id > 0) {
  $objEmp->delete_team(('id' => $id));
}

print dot('employee.edit.team.delete', $_REQUEST);

?>
