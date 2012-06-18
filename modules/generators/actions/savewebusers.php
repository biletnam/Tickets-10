<?php

$id = lavnn('generator_id', $_REQUEST, 0);
if ($id > 0) {
  $ids = lavnn('ids');
  foreach $id (@ids) {
    $password = lavnn("password_$id", $_REQUEST, '');
    if ($password <> '') {
      srun($module, 'ChangeUserPassword', array('user_id' => $id, 'password' => trim($password)));
    }
  }
  go("?p=$module/view&id=$id&tab=webaccess");
} else {
  go("?p=$module/search");
}
?>
