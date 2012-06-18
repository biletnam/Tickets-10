<?php

$pageParams  = array();
$pageParams['resorts'] = arr2ref(s2a($module, 'ListExtResorts')); 
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.extresorts');
$page->add('main', $runtime->txt->do_template($module, 'extresorts', $pageParams);



  
?>
