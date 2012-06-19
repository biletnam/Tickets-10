<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun($module, 'UpdateContactType', $_REQUEST);
  $_SESSION['flash'] = 'Contact type updated');
} else {
  $newid = sid($module, 'AddContactType', $_REQUEST);
  if ($newid > 0) {
    $_SESSION['flash'] = 'Contact type added');
  } else {
    set_cookie('error', 'Failed to add contact type');
  }
}

go("?p=$module/settings&tab=contacttypes");

?>
