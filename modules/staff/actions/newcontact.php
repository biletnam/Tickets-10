<?php

use objStaffManagement;
$objSM = new objStaffManagement($r);

$user_id = lavnn('user_id');
$key = lavnn('key');
$pageParams = array('key' => $key, 'id' => sprintf("%d", time()), 'user_id' => $user_id);

$available = array();
$allcontacts = $objSM->get_all_contact_types();
$all_by_type = slice_array($allcontacts, 'type'); 

$existingcontacts = $objSM->get_employee_contact_info($user_id, $key, 0, 1);
$existing_by_id = slice_array($existingcontacts, 'contact_type_id'); 
foreach $ctype ($all_by_type{$key}}) {
  $ctypeid = $ctype['id'];
  if ($ctype['is_multiple'] == '1' || !exists $existing_by_id{$ctypeid}) {
    push @available, $ctype;
  }
}
if (count($available) > 0) {
  $pageParams['all'] = arr2ref(genOptions($available, 'id', 'name'));
  print dot('employee.edit.contact.newitem', $pageParams);
}
1;


?>
