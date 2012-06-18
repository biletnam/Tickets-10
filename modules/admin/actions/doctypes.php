<?php

$pageParams  = array();
$pageParams['doctypes'] = arr2ref(s2a($module, 'ListDocTypes'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'doctypes.title');
$page->add('main', $runtime->txt->do_template($module, 'doctypes', $pageParams);


  
?>
