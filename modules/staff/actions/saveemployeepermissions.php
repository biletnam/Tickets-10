<?php

use objStaffManagement;
$objSM = new objStaffManagement($r);

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $objSM->save_user_permissions(%_REQUEST);
  set_cookie('flash', 'User permissions updated');
  go("?p=$module/employee&id=$id#special");
} else {
  go("?p=$module/offices");
}


?>
