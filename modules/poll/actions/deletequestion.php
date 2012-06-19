<?php

$id = lavnn('id');
if ($id != 0) {
  $questionData = $runtime->s2r($module, 'GetQuestionDetails', $_REQUEST);
  if (count($questionData) > 0) {
    $poll = $questionData['poll'];
    $deleted = $objP->delete_question(%_REQUEST);
    if ($deleted > 0) {
      $_SESSION['flash'] = 'question data deleted');
      # do additional database deletions depending on question type
    }
    go("?p=$module/edit&id=$poll&tab=questions");
  } 
}

go("?p=$module/list&tab=editlist");

1;

?>
