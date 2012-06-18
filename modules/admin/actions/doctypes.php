<?php

$pageParams  = array();
$pageParams['doctypes'] = arr2ref(s2a($module, 'ListDocTypes'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'doctypes.title');
$page->add('main', $runtime->doTemplate($module, 'doctypes', $pageParams);


  
?>
