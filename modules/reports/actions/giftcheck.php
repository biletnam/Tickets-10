<?php
use ctlDataGrid;

$pageParams = array();  
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.giftcheck');
$report = lavnn('report', $_REQUEST, '');
if ($report <> '' && $runtime->eqmod($module, $report)) {

    $results = $runtime->s2a($module, $report, $reportParams); 
    $pageParams['rows'] = $results;
    $pageParams['results'] = $runtime->txt->do_template($module, 'giftcheck.results', $pageParams);

}
$pageParams['reportname'] = $report;
$page->add('main', $runtime->txt->do_template($module, 'giftcheck', $pageParams);




?>
