<?php

use Calendar;
use DateChemistry;
$today = Calendar::getTodayArr();
$pageParams = array();

#print spreview($module, 'GetTodayReminders', array('user_id' => $r['userID'], 'actiondate' => DateChemistry::fulldate2mysql($today['fulldate'])));

$today_items = $runtime->s2a($module, 'GetTodayReminders', array('user_type' => 'U', 'user_id' => $r['userID']));
if (count($today_items) > 0) {
  $pageParams['todayitems'] = $today_items; 
  $pageParams['today'] = $runtime->txt->do_template($module, 'dashboard.planner.today', $pageParams);
} else {
  $pageParams['today'] = $runtime->txt->do_template($module, 'dashboard.planner.today.empty');
}

$overdue_items = $runtime->s2a($module, 'GetOverdueReminders', array('user_type' => 'U', 'user_id' => $r['userID']));
if (count($overdue_items) > 0) {
  #print Dumper($overdue_items);
  $pageParams['overdueitems'] = $overdue_items;
  $pageParams['overdue'] = $runtime->txt->do_template($module, 'dashboard.planner.overdue', $pageParams);
} 

$future_items = $runtime->s2a($module, 'GetFutureReminders', array('user_type' => 'U', 'user_id' => $r['userID']));
if (count($future_items) > 0) {
  $pageParams['futureitems'] = $future_items; 
  $pageParams['future'] = $runtime->txt->do_template($module, 'dashboard.planner.future', $pageParams);
} else {
  $pageParams['future'] = $runtime->txt->do_template($module, 'dashboard.planner.future.empty');
}

print $runtime->txt->do_template($module, 'dashboard.planner', $pageParams);
?>
