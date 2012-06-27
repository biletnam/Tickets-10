<?php

$pageParams = array();
$officeProblems = $runtime->s2a($module, 'ListProblems');
$pageParams['officeproblems'] = $officeProblems;
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.problemreport');
$page->add('main', $r->txt->do_template($module, 'problemreport', $pageParams);

?>
