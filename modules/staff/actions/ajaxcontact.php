<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $contactInfo = $runtime->s2r($module, 'GetEmployeeContact', $_REQUEST);
  print dot('employee.edit.contact.listitem', $contactInfo);
}

?>
