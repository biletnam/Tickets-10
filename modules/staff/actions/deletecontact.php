<?php

$id = lavnn('id', $_REQUEST, 0);
use objEmployee;
$objEmp = new objEmployee($r);

if ($id > 0) {
  $objEmp->delete_contact(('id' => $id));
}

print $r->txt->do_template($module, 'employee.edit.contact.delete', $_REQUEST);

?>
