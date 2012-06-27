<?php
$pageParams = $_REQUEST;
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.lastdeductions');
$pageParams['deductions'] = arr2ref(s2a($module, 'ListLastDeductions'));
$page->add('main', $r->txt->do_template($module, 'lastdeductions', $pageParams);



?>
