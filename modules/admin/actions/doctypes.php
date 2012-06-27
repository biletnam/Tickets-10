<?php

$pageParams  = array();
$pageParams['doctypes'] = arr2ref(s2a($module, 'ListDocTypes'));
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'doctypes.title');
$page->add('main', $r->txt->do_template($module, 'doctypes', $pageParams);


  
?>
