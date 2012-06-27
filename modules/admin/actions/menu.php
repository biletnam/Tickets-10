<?php

$pageParams  = array();
$pageParams['menuitems'] = arr2ref(s2a($module, 'GetFullMenu'));
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'menu.title');
$page->add('main', $r->txt->do_template($module, 'menu', $pageParams);



  
?>
