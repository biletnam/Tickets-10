<?php

use objStaffManagement;
$objSM = new objStaffManagement($r);

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $_REQUEST['tags'] =~ s/,\s+/, /g; # fix all spaces after separating commas
  $_REQUEST['tags'] =~ s/\s+,/,/g; # fix all spaces before separating commas
  $objSM->save_user_specialdata(%_REQUEST);
  set_cookie('flash', 'User special info updated');
  go("?p=$module/employee&id=$id#special");
} else {
  go("?p=$module/offices");
}


?>
