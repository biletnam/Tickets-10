<?php

$pageParams = array();
$officeProblems = $runtime->s2a($module, 'ListProblems');
$pageParams['officeproblems'] = $officeProblems;
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.problemreport');
$page->add('main', $runtime->txt->do_template($module, 'problemreport', $pageParams);

?>
