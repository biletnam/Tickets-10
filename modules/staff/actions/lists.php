<?php

$pageParams = array();
$lists =  s2a($module, 'Lists'); 
  
$pageParams['lists'] = $lists;

$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.lists');
$page->add('main', $r->txt->do_template($module, 'lists', $pageParams);



?>
