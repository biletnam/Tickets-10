<?php

$id = lavnn('id');
if ($id != 0) {
  #formdebug($_REQUEST); die(spreview($module, 'UpdatePoll', $_REQUEST));
  $runtime->db->sqlrun($module, 'UpdatePoll', $_REQUEST);
  $_SESSION['flash'] = 'Poll data updated');
  go("?p=$module/edit&id=$id");
} else {
  go("?p=$module/list&tab=editlist");
}

1;
?>
