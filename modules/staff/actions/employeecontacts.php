<?php


$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $personInfo = $runtime->s2r($module, 'GetEmployeeDetails', $_REQUEST); 
  
  if (count($personInfo)) {
    use objStaffManagement;
    $objSM = new objStaffManagement($r);
    if ($acc->can_edit_staff($personInfo['lngWorkPlace'])) {
  
      # Get list of user contacts
      $contacts = $objSM->get_employee_contact_info($id, '', 0, 1);
      $defined_by_type = slice_array($contacts, 'contact_type');

      $allcontacts = $objSM->get_all_contact_types();
      $all_by_type = slice_array($allcontacts, 'type'); 

      while (($key, $value) = each %all_by_type) {
        $defined = $defined_by_type{$key]; 
        if (exists $defined_by_type{$key}) {
          push @types, dot('employee.edit.contact.group', array('user_id' => $id, 'key' => $key, 'defined' => $defined));
        } else {
          push @types, dot('employee.edit.contact.group', array('user_id' => $id, 'key' => $key));
        } 
      }      

      print join('', $types);
    } else {
      $contacts = $objSM->get_employee_contact_info($id, '', 0, 1); 
      $defined_by_type = slice_array($contacts, 'contact_type');
      $personInfo['contacts'] = $defined_by_type;
      print $runtime->txt->do_template($module, 'employee.view.contact', $personInfo);
    }
  } else {
    print $runtime->txt->do_template($module, 'employee.notfound');
  }
} else {
  print 'Invalid call';
}
 
1;
?>
