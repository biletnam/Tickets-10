<?php

$pageParams  = array();
$pageParams['seasons'] = arr2ref(s2a($module, 'ListExtSeasons')); 
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.extseasons');
$page->add('main', $r->txt->do_template($module, 'extseasons', $pageParams);



  
?>
