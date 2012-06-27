<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $contactInfo = $runtime->s2r($module, 'GetEmployeeContact', $_REQUEST);
  print $r->txt->do_template($module, 'employee.edit.contact.listitem', $contactInfo);
}

?>
