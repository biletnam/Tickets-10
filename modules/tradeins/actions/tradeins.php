<?php

$pageParams  = array();
$pageParams['weeks'] = arr2ref(s2a($module, 'ListTradeinWeeks'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.tradeins');
$page->add('main', $runtime->doTemplate($module, 'tradeins', $pageParams);

$page['js'] = $runtime->doTemplate($module, 'tradeins.js');


  
?>
