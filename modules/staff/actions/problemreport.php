<?php

$pageParams = array();
$officeProblems = $runtime->s2a($module, 'ListProblems');
$pageParams['officeproblems'] = $officeProblems;
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.problemreport');
$page->add('main', $runtime->doTemplate($module, 'problemreport', $pageParams);

?>
