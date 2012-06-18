<?php

$pageParams = array();
$lists =  s2a($module, 'Lists'); 
  
$pageParams['lists'] = $lists;

$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.lists');
$page->add('main', $runtime->doTemplate($module, 'lists', $pageParams);



?>
