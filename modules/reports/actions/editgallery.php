<?php
$pageParams = array();

$reports = $runtime->s2a($module, 'ListPredefinedReports');
$pageParams['reports'] = $reports;
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.editgallery');
$page->add('main', $runtime->doTemplate($module, 'editgallery', $pageParams);




?>
