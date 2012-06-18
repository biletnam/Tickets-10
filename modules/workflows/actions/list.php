<?php

$pageParams  = array();
$pageParams['workflows'] = arr2ref(s2a($module, 'ListWorkflows')); 
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'list.title');
$page->add('main', $runtime->doTemplate($module, 'list', $pageParams);



  
?>
