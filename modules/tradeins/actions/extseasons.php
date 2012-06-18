<?php

$pageParams  = array();
$pageParams['seasons'] = arr2ref(s2a($module, 'ListExtSeasons')); 
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.extseasons');
$page->add('main', $runtime->doTemplate($module, 'extseasons', $pageParams);



  
?>
