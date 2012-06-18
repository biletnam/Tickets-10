<?php
$pageParams = %_REQUEST;
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.lastdeductions');
$pageParams['deductions'] = arr2ref(s2a($module, 'ListLastDeductions'));
$page->add('main', $runtime->doTemplate($module, 'lastdeductions', $pageParams);



?>
