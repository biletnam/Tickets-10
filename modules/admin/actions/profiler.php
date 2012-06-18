<?php

$pageParams  = array();
$pageParams['profiler'] = arr2ref(s2a($module, 'ListProfilerRecords'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'profiler.title');
$page->add('main', $runtime->txt->do_template($module, 'profiler', $pageParams);


  
?>
