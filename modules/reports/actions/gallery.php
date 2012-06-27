<?php
$pageParams = array();

$resources = $acc->list_access('runreport', $r['userID']);
$reports = $runtime->s2a($module, 'ListPredefinedReports', array('ids' => join_column(',', 'source_id', $resources)));
$pageParams['reports'] = $reports;
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.gallery');
$page->add('main', $r->txt->do_template($module, (count($reports) == 0 ? 'gallery.nodata' : 'gallery'), $pageParams);




?>
