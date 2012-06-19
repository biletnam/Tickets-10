<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $result = $runtime->db->sqlrun($module, 'DeleteContactType', $_REQUEST);
  if ($result > 0) {
    $_SESSION['flash'] = 'Contact type deleted');
  } else {
    set_cookie('error', 'Contact type could not be deleted');
  }
}

go("?p=$module/settings&tab=contacttypes");

?>
