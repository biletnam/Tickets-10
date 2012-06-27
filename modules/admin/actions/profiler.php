<?php

$pageParams  = array();
$pageParams['profiler'] = arr2ref(s2a($module, 'ListProfilerRecords'));
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'profiler.title');
$page->add('main', $r->txt->do_template($module, 'profiler', $pageParams);


  
?>
