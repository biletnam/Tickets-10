<?php

$pageParams  = array();
$pageParams['robots'] = arr2ref(s2a($module, 'ListRobotLogs'));
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'robots.title');
$page->add('main', $r->txt->do_template($module, 'robots', $pageParams);


  
?>
