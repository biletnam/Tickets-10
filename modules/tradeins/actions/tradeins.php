<?php

$pageParams  = array();
$pageParams['weeks'] = arr2ref(s2a($module, 'ListTradeinWeeks'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.tradeins');
$page->add('main', $runtime->txt->do_template($module, 'tradeins', $pageParams);

$page['js'] = $runtime->txt->do_template($module, 'tradeins.js');


  
?>
