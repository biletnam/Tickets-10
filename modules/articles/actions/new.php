<?php

$pageParams = array();
$user_id = $pageParams['user_id'] = $r['userInfo']['staff_id'];
$draft = $runtime->s2r($module, 'GetArticleDraft', array('user_id' => $user_id, 'article_id' => ''));
if (count($draft) > 0) {
  $pageParams['draft'] = $runtime->doTemplate($module, 'restore', $draft);
}
$pageParams['tags'] = lavnn('tags', $_REQUEST, '');

$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.new');
$page->add('main', $runtime->doTemplate($module, 'new', $pageParams);



?>
