<?php

use ctlTab;
 
$pageParams = array();
$user_id = lavnn('user');
$user_id = $r['userID'] if $user_id == '';
$pageParams['user_id'] = $user_id; 
$userInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $user_id)); 
$lastweek = $runtime->s2a($module, 'GetUserReportingLastWeek', array('user_id' => $user_id)); 
$thisweek = $runtime->s2a($module, 'GetUserReportingThisWeek', array('user_id' => $user_id)); 
$bymonth = $runtime->s2a($module, 'GetUserReportingByMonth', array('user_id' => $user_id)); 
$runtime->saveMoment('  reporting data fetched from db');

$lastweek_byday = slice_array($lastweek, 'ReportingDate'); $lastweek = array();
$thisweek_byday = slice_array($thisweek, 'ReportingDate'); $thisweek = array();
$runtime->saveMoment('  reporting data sliced by day');

$thisweek_totalminutes = 0;
foreach $date (sort (keys(%thisweek_byday))) {
  $dayreport = array('date' => $date);
  $dayreports = $thisweek_byday{$date];
  $dayreport['total'] = Arrays::sum_column($dayreports, 'total_minutes');
  $dayreport['details'] = $runtime->txt->do_template($module, 'usertime.dayreport.list', array('reports' => $dayreports));
  unshift @thisweek, dot('usertime.day', $dayreport);
  $thisweek_totalminutes += $dayreport['total'];
}
$pageParams['thisweekminutes'] = $thisweek_totalminutes;
$runtime->saveMoment('  this week is rendered');

$lastweek_totalminutes = 0;
foreach $date (sort (keys(%lastweek_byday))) {
  $dayreport = array('date' => $date);
  $dayreports = $lastweek_byday{$date];
  $dayreport['total'] = Arrays::sum_column($dayreports, 'total_minutes');
  $dayreport['details'] = $runtime->txt->do_template($module, 'usertime.dayreport.list', array('reports' => $dayreports));
  unshift @lastweek, dot('usertime.day', $dayreport);
  $lastweek_totalminutes += $dayreport['total'];
}
$pageParams['lastweekminutes'] = $lastweek_totalminutes;
$runtime->saveMoment('  last week is rendered');

$time_by_months = '';
if (count($bymonth) > 0) {
  $byyear = slice_array($bymonth, 'Y'); 
  foreach $Y (reverse sort keys %byyear) {
    $time_by_months .= $runtime->txt->do_template($module, 'usertime.year', array('year' => $Y, 'months' => loopt('usertime.year.month', $byyear{$Y}})));
  }
  $time_by_months = $runtime->txt->do_template($module, 'usertime.bymonths', array('selector' => $time_by_months));
  
}

$tabUserTime = new ctlTab($r, "tcUserTime"); 
$tabUserTime->addTab('thisweek', dot('usertime.thisweek.tabheader', $pageParams), join('', @thisweek)) if (count($thisweek) > 0);
$tabUserTime->addTab('lastweek', dot('usertime.lastweek.tabheader', $pageParams), join('', @lastweek)) if (count($lastweek) > 0);
$tabUserTime->addTab('bymonth', dot('usertime.bymonth.tabheader', $pageParams), $time_by_months) if (count($bymonth) > 0);
$tabUserTime->addTab('report', dot('usertime.report.tabheader', $pageParams), dot('usertime.report', $pageParams));
$pageParams['tabcontrol'] = $tabUserTime->getHTML();
       
$page['js'] = $runtime->txt->do_template('main', 'tabcontrol.js');
$page['css'] = $runtime->txt->do_template('main', 'tabcontrol.css');
if ($r['userID'] == $user_id) {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.mytime', $pageParams);  
} else {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.usertime', $userInfo);  
}
$page->add('main', $runtime->txt->do_template($module, 'usertime', $pageParams);


?>
