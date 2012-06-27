<?php
$pageParams = array();

$reports = $runtime->s2a($module, 'ListPredefinedReports');
$pageParams['reports'] = $reports;
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.editgallery');
$page->add('main', $r->txt->do_template($module, 'editgallery', $pageParams);




?>
