<?php
$pageParams = array();

$reports = $runtime->s2a($module, 'ListPredefinedReports');
$pageParams['reports'] = $reports;
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.editgallery');
$page->add('main', $runtime->txt->do_template($module, 'editgallery', $pageParams);




?>
