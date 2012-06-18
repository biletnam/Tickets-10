<?php

$pageParams  = array();
$pageParams['robots'] = arr2ref(s2a($module, 'ListRobotLogs'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'robots.title');
$page->add('main', $runtime->doTemplate($module, 'robots', $pageParams);


  
?>
