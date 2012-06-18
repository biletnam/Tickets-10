<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'UpdateContactType', $_REQUEST);
  set_cookie('flash', 'Contact type updated');
} else {
  $newid = sid($module, 'AddContactType', $_REQUEST);
  if ($newid > 0) {
    set_cookie('flash', 'Contact type added');
  } else {
    set_cookie('error', 'Failed to add contact type');
  }
}

go("?p=$module/settings&tab=contacttypes");

?>
