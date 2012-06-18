<?php
$pageParams = array();

$resources = $acc->list_access('runreport', $r['userID']);
$reports = $runtime->s2a($module, 'ListPredefinedReports', array('ids' => Arrays::join_column(',', 'source_id', $resources)));
$pageParams['reports'] = $reports;
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.gallery');
$page->add('main', $runtime->doTemplate($module, (count($reports) == 0 ? 'gallery.nodata' : 'gallery'), $pageParams);




?>
