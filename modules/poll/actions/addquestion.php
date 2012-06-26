<?php

$id = $runtime->sid($module, 'InsertPollQuestion', $_REQUEST);
$poll = lavnn('poll');
if ($id > 0) {
  $questionInfo = $runtime->s2r($module, 'GetQuestionDetails', $_REQUEST);
  if ($questionInfo['question_type'] == 'dropdown') {
    $_SESSION['flash'] = "New question of dropdown type is added, continue editing it below.");
    go("?p=$module/editquestion&id=$id");
  } else {
    $_SESSION['flash'] = "New question is added");
  }
} 

if ($poll > 0) {
  go("?p=$module/edit&id=$poll");
} else {
  go("?p=$module/list&tab=editlist");
}

1;

?>
