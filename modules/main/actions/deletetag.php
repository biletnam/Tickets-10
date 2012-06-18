<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'DeleteTag', $_REQUEST);
}

1;
?>
