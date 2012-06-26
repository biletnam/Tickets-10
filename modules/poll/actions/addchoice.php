<?php

$question = lavnn('question');
$numeric_value = lavnn('question');
$choice = lavnn('question');
if ($choice <> '' && $numeric_value <> '') {
  $id = $runtime->sid($module, 'AddQuestionChoice', $_REQUEST);
}
if ($question <> '') {
  go("?i=$module/choices&question=$question");
}

?>
