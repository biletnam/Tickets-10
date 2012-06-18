<?php

$id = lavnn('id', $_REQUEST, 0);
$pageParams = array();
if ($id > 0) {
  $accesses = $acc->list_users_for_resource('readarticle', $id);
  $ids = Arrays::join_column(',', 'lngId', $accesses);
  $respondents = $runtime->s2a($module, 'ListEmpFormSubmits', array('article_id' => $id, 'ids' => $ids));
  print spreview($module, 'ListEmpFormSubmits', array('article_id' => $id, 'ids' => $ids));
#  print Dumper($respondents);
  $pageParams['respondents'] = $respondents;  
}
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.empform', $pageParams);
$page->add('main', $runtime->doTemplate($module, 'empform', $pageParams);

      
?>
