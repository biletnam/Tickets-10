<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id <> '') {
  $runtime->db->sqlrun($module, 'ResetForwarder', $_REQUEST);
}

go("?i=$module/promptsetfwd&id=$id");

?>
