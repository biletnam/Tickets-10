<?php

$pageParams  = array();
$pageParams['menuitems'] = arr2ref(s2a($module, 'GetFullMenu'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'menu.title');
$page->add('main', $runtime->txt->do_template($module, 'menu', $pageParams);



  
?>
