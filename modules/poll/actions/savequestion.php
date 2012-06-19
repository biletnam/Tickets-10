<?php

$id = lavnn('id');
if ($id != 0) {
  $runtime->db->sqlrun($module, 'UpdatePollQuestion', $_REQUEST);
  $_SESSION['flash'] = 'question data updated');
  $poll = lavnn('poll');
  if ($poll > 0) {
    go("?p=$module/edit&id=$poll&tab=questions");
  } else {
    go("?p=$module/editquestion&id=$id");
  }
} else {
  go("?p=$module/list&tab=editlist");
}

1;

?>
