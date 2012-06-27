<?php

use ctlHypercube;

$pageParams = array();
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'reports.title');

$reportHtml = '';
$report = lavnn('report', $_REQUEST, '');
if ($report == 'activity') {
  $reportdata = $runtime->s2a($module, 'ReportActivity'); 
  $activetickets = $reportdata[0]['ticketsinprogress'];
  $latestticket = $reportdata[0]['latestticket'];
  $pageParams['reportdata'] = $reportdata;
  $pageParams['activetickets'] = $activetickets;
  $pageParams['latestticket'] = $latestticket;
  $bymonth = $runtime->s2a($module, 'ReportActivityByMonth'); 
  $pageParams['bymonth'] = $bymonth;
  $reportHtml = $r->txt->do_template($module, 'reports.activity', $pageParams);
} elseif ($report == 'actors') {
  $actors = $runtime->s2a($module, 'ReportActors'); 
  $pageParams['actors'] = $actors;
  $reportHtml = $r->txt->do_template($module, 'reports.actors', $pageParams);
} elseif ($report == 'projects') {
  $projects = $runtime->s2a($module, 'ListProjects', array('adminmode' => 'yes')); 
  $pageParams['projects'] = $projects;
  $reportHtml = $r->txt->do_template($module, 'reports.projects', $pageParams);
}
$pageParams['report'] = $reportHtml;
$page->add('main', $r->txt->do_template($module, 'reports', $pageParams);


      
?>
