<?php

$_REQUEST['created_by'] = $r['userID'];

$contact_id = $_REQUEST['id'] = $runtime->sid($module, 'AddEmployeeContact', $_REQUEST); 
if ($contact_id > 0) {
  use objEmployee;
  $objEmp = new objEmployee($r); 
  $objEmp->sync_contact(('id' => $contact_id));
  print $r->txt->do_template($module, 'employee.edit.contact.save', $_REQUEST); 
}

1;

?>
