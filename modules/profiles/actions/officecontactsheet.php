<?php

$id = $_REQUEST['office'] = lavnn('id', $_REQUEST, 0); 
if ($id <> 0) {
  $officeData = $runtime->s2r($module, 'GetOfficeDetails', $_REQUEST);
  $_REQUEST['sort'] = 'DepartmentName, p.strLastName, p.strFirstName';
  $contacts = $runtime->s2a($module, 'ListStaff', $_REQUEST); 
  $departments = Arrays::slice_array($contacts, 'DepartmentName');
  $officeData['contacts'] = $departments;
  print dot('office.contactsheet', $officeData); 
}

?>