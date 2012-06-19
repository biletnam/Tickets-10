<?php

$id = lavnn('id');
if ($id != 0) {
  $newid = sid($module, 'ClonePollQuestion', $_REQUEST);
  $qInfo = $runtime->s2r($module, 'GetQuestionDetails', array('id' => $newid));
  $poll = $qInfo['poll'] || 0;
  if ($poll > 0) {
    $runtime->db->sqlrun($module, 'CopyPollQuestionChoices', array('new' => $newid, 'old' => $id));
    $_SESSION['flash'] = 'Question data cloned, please do not forget to edit a copy!');
    go("?p=$module/edit&id=$poll&tab=questions");
  } else {
    go("?p=$module/editquestion&id=$id");
  }
} else {
  go("?p=$module/list&tab=editlist");
}

1;

?>
