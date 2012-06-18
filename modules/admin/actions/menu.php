<?php

$pageParams  = array();
$pageParams['menuitems'] = arr2ref(s2a($module, 'GetFullMenu'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'menu.title');
$page->add('main', $runtime->doTemplate($module, 'menu', $pageParams);



  
?>
