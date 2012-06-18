<?php

$poll = lavnn('id');
if ($poll > 0) {
  $pollInfo = $runtime->s2r($module, 'GetPollDetails', array('id' => $poll, 'user_type' => 'U', 'user_id' => $r['userID']));
  if (count($pollInfo) == 0) {
    set_cookie('error', 'Poll not found');
  } elseif ($pollInfo['can_change'] <> 1 && $pollInfo['DateTaken'] <> '') {
    set_cookie('error', 'You can not answer twice to the same poll!');
  } else {
    if ($pollInfo['DateTaken'] <> '') {
      srun($module, 'UpdatePollDateTaken', array('poll' => $poll, 'user_type' => 'U', 'user_id' => $r['userID']));
    } else {
      srun($module, 'MarkPollAsTaken', array('poll' => $poll, 'user_type' => 'U', 'user_id' => $r['userID']));
    } 
    $ids = lavnn('questions');
    $cnt = 0;
#    formdebug($_REQUEST); 
    foreach $id (@ids) {
      $qtype = lavnn("type_$id") || 'generic';
      $answerInfo = ('question_id' => $id, 'user_id' => $r['userID'], 'user_type' => 'U', 'answer_id' => lavnn("answer_id_$id"));
      if ($qtype == 'dropdown' || $qtype == 'yesno') {
        $answerInfo['numeric_answer'] = lavnn("answer_$id");
      } elseif ($qtype == 'generic') {
        $answerInfo['string_answer'] = lavnn("answer_$id");
      }
#      formdebug($answerInfo); print spreview($module, 'InsertPollAnswer', $answerInfo);
      if ($pollInfo['DateTaken'] <> '') { # update old asnwer
        srun($module, 'UpdatePollAnswer', $answerInfo); $cnt++;
      } else { # insert new answer
        $cnt++ if 0 < sid($module, 'InsertPollAnswer', $answerInfo);
      }
    }
#    die();
    if ($cnt == count($id)) {
      set_cookie('flash', 'Thanks for answering! Your vote is saved.');
    } # TODO else warn about error, rollback DateTaken and delete those answers that were made
  }

}

go("?p=$module/list&tab=mypolls");

?>
