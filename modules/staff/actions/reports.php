<?php

$pageParams = array();
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'reports.title');

$reportHtml = '';
$report = lavnn('report', $_REQUEST, '');
if ($report == 'office_dep_stats') {
  $pageParams['items'] = arr2ref(s2a($module, 'ReportOfficeDepStats'));
  $reportHtml = $runtime->txt->do_template($module, 'reports.office_dep_stats', $pageParams); 
} elseif ($report == 'dep_office_stats') {
  $pageParams['items'] = arr2ref(s2a($module, 'ReportDepOfficeStats'));
  $reportHtml = $runtime->txt->do_template($module, 'reports.office_dep_stats', $pageParams); 
}
$pageParams['report'] = $reportHtml;
$page->add('main', $runtime->txt->do_template($module, 'reports', $pageParams);


      
?>
