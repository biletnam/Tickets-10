<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $pageParams = $runtime->s2r($module, 'GetEmployeeDetails', array('id' => $id));
  $articles = $runtime->s2a($module, 'ListUserArticles', array('id' => $id));
  $pageParams['articles'] = $articles;
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'title.userarticles', $pageParams);
  $page->add('main', $r->txt->do_template($module, 'userarticles', $pageParams);
}



?>
