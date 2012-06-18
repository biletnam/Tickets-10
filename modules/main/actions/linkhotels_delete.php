<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  srun($module, 'DeleteHotelsLink', $_REQUEST);
  print "OK";
} else {
  print "";
}

?>
