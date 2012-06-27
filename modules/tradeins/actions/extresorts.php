<?php

$pageParams  = array();
$pageParams['resorts'] = arr2ref(s2a($module, 'ListExtResorts')); 
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.extresorts');
$page->add('main', $r->txt->do_template($module, 'extresorts', $pageParams);



  
?>
