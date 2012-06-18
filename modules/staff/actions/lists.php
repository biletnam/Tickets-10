<?php

$pageParams = array();
$lists =  s2a($module, 'Lists'); 
  
$pageParams['lists'] = $lists;

$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.lists');
$page->add('main', $runtime->txt->do_template($module, 'lists', $pageParams);



?>
