<?php

$id = lavnn('user_id', $_REQUEST, 0);
if ($id > 0) {
  $phonetypes = $runtime->getDictArr('main', 'employeephonetype');
  foreach $phonetype (@phonetypes) {
    $phone_type = $phonetype['key']; 
    $phone_number = $_REQUEST['phone_'.$phone_type} || '';
    $phone_id = $_REQUEST['phone_'.$phone_type.'_id'] || 0;
    $runtime->db->sqlrun($module, 'SetUserPhone', array('user_id' => $id, 'id' => $phone_id, 'key' => $phone_type, 'number' => $phone_number)); 
  }
  $_SESSION['flash'] = 'User phones updated');
  go("?p=staff/employee&id=$id&tab=phones");
} else {
  go("?p=staff/offices");
}
?>
