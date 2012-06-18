<?php

$_REQUEST['created_by'] = $r['userID'];

$contact_id = $_REQUEST['id'] = sid($module, 'AddEmployeeContact', $_REQUEST); 
if ($contact_id > 0) {
  use objEmployee;
  $objEmp = new objEmployee($r); 
  $objEmp->sync_contact(('id' => $contact_id));
  print dot('employee.edit.contact.save', $_REQUEST); 
}

1;

?>
