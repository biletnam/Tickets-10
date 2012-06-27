<?php

$pageParams  = array();
$pageParams['weeks'] = arr2ref(s2a($module, 'ListTradeinWeeks'));
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.tradeins');
$page->add('main', $r->txt->do_template($module, 'tradeins', $pageParams);

$page['js'] = $r->txt->do_template($module, 'tradeins.js');


  
?>
