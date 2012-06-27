<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $questionInfo = $objP->get_question_details($_REQUEST); 
  $runtime->saveMoment('  fetched question data from db');
#  print Dumper($questionInfo);
  $page->add('title',  $questionInfo['pagetitle'] = $r->txt->do_template($module, 'title.editquestion', $questionInfo);
  $questionInfo['rendered_question'] = $objP->render_question($questionInfo, 'edit');
  $page->add('main', $r->txt->do_template($module, 'editquestion', $questionInfo);
}





?>
