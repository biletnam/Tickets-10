<?php

$id = lavnn('id', $_REQUEST, '');
if ($id == '') {
  $_REQUEST['editor'] = $r['userID'];
  $_REQUEST['id'] = $id = sid($module, 'InsertPerson', $_REQUEST); # TODO Implement adding a person!
  if ($id > 0) {
    set_cookie('flash', 'Employee added');
  } else {
    set_cookie('error', 'Could not add employee');
    go("?p=$module/offices"); exit();
  }
} else {
  srun($module, 'UpdatePerson', $_REQUEST);  
  set_cookie('flash', 'Employee updated');
}
if ($id > 0) {
#  srun($module, 'TransferContactInfo', array('id' => $id, 'editor' => $r['userID']));
}

go("?p=$module/employee&id=$id");

?>
