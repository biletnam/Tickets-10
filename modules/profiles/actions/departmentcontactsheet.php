<?php

$id = $_REQUEST['department'] = lavnn('id', $_REQUEST, 0); 
if ($id <> 0) {
  $departmentData = $runtime->s2r($module, 'GetDepartmentDetails', $_REQUEST);
  $_REQUEST['sort'] = 'OfficeName, p.strLastName, p.strFirstName';
  $contacts = $runtime->s2a($module, 'ListStaff', $_REQUEST); 
  $offices = slice_array($contacts, 'OfficeName');
  $departmentData['contacts'] = $offices;
  print $r->txt->do_template($module, 'department.contactsheet', $departmentData); 
}

?>
