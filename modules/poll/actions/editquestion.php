<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $questionInfo = $objP->get_question_details(%_REQUEST); 
  $runtime->saveMoment('  fetched question data from db');
#  print Dumper($questionInfo);
  $page->add('title',  $questionInfo['pagetitle'] = $runtime->doTemplate($module, 'title.editquestion', $questionInfo);
  $questionInfo['rendered_question'] = $objP->render_question($questionInfo, 'edit');
  $page->add('main', $runtime->doTemplate($module, 'editquestion', $questionInfo);
}





?>
