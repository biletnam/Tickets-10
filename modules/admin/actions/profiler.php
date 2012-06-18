<?php

$pageParams  = array();
$pageParams['profiler'] = arr2ref(s2a($module, 'ListProfilerRecords'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'profiler.title');
$page->add('main', $runtime->doTemplate($module, 'profiler', $pageParams);


  
?>
