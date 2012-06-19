<?php

$id = lavnn('question');
$choiceDetails = $runtime->s2r($module, 'GetQuestionChoiceDetails', $_REQUEST);
$question = $choiceDetails['question'] || ''; 
$runtime->db->sqlrun($module, 'DeleteQuestionChoice', $_REQUEST);
if ($question <> '') {
  go("?i=$module/choices&question=$question");
} else {
  go("?i=main/ajaxfailure");
}

?>
