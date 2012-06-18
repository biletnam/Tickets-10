<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $result = srun($module, 'DeleteContactType', $_REQUEST);
  if ($result > 0) {
    set_cookie('flash', 'Contact type deleted');
  } else {
    set_cookie('error', 'Contact type could not be deleted');
  }
}

go("?p=$module/settings&tab=contacttypes");

?>
