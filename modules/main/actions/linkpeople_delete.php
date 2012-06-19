<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $runtime->db->sqlrun($module, 'DeletePersonLink', $_REQUEST);
  print "OK";
} else {
  print "";
}

?>
