<?php

$pageParams  = array();
$pageParams['resorts'] = arr2ref(s2a($module, 'ListExtResorts')); 
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.extresorts');
$page->add('main', $runtime->doTemplate($module, 'extresorts', $pageParams);



  
?>
