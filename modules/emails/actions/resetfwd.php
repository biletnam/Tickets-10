<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id <> '') {
  srun($module, 'ResetForwarder', $_REQUEST);
}

go("?i=$module/promptsetfwd&id=$id");

?>
