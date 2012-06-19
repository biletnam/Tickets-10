<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $runtime->db->sqlrun($module, 'DeleteHotelsLink', $_REQUEST);
  print "OK";
} else {
  print "";
}

?>
