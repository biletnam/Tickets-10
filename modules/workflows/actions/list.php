<?php

$pageParams  = array();
$pageParams['workflows'] = arr2ref(s2a($module, 'ListWorkflows')); 
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'list.title');
$page->add('main', $r->txt->do_template($module, 'list', $pageParams);



  
?>
