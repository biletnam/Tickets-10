<?php

$pageParams = array();
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'reports.title');

$reportHtml = '';
$report = lavnn('report', $_REQUEST, '');
if ($report == 'office_dep_stats') {
  $pageParams['items'] = arr2ref(s2a($module, 'ReportOfficeDepStats'));
  $reportHtml = $r->txt->do_template($module, 'reports.office_dep_stats', $pageParams); 
} elseif ($report == 'dep_office_stats') {
  $pageParams['items'] = arr2ref(s2a($module, 'ReportDepOfficeStats'));
  $reportHtml = $r->txt->do_template($module, 'reports.office_dep_stats', $pageParams); 
}
$pageParams['report'] = $reportHtml;
$page->add('main', $r->txt->do_template($module, 'reports', $pageParams);


      
?>
