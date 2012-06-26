<?php

$officeData = $runtime->s2r($module, 'GetOfficeDetails', $_REQUEST);
$_REQUEST['sort'] = 'o.DepartmentName, ss.name, p.strLastName, p.strFirstName';
$contacts = $runtime->s2a($module, 'ListStaff', $_REQUEST); 
$departments = slice_array($contacts, 'DepartmentName');
$dd = array();
while (($key, $value) = each %departments) {
  $subteams = slice_array($value, 'SubteamName');
  push @dd, dot('office.contactsheet.department', array('key' => $key, 'contacts' => $subteams));
}
$officeData['contacts'] = join('', $dd);
print $runtime->txt->do_template($module, 'office.contactsheet', $officeData);

?>
