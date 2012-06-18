<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  srun($module, 'DeletePersonLink', $_REQUEST);
  print "OK";
} else {
  print "";
}

?>
