<?php

$id = lavnn('id', $_REQUEST, '');
if ($id == '') {
  $_REQUEST['editor'] = $r['userID'];
  $_REQUEST['id'] = $id = sid($module, 'InsertPerson', $_REQUEST); # TODO Implement adding a person!
  if ($id > 0) {
    $_SESSION['flash'] = 'Employee added');
  } else {
    set_cookie('error', 'Could not add employee');
    go("?p=$module/offices"); exit();
  }
} else {
  $runtime->db->sqlrun($module, 'UpdatePerson', $_REQUEST);  
  $_SESSION['flash'] = 'Employee updated');
}
if ($id > 0) {
#  $runtime->db->sqlrun($module, 'TransferContactInfo', array('id' => $id, 'editor' => $r['userID']));
}

go("?p=$module/employee&id=$id");

?>
