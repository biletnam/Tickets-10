<?php

use Calendar;
use DateChemistry;
$today = Calendar::getTodayArr();
$pageParams = array();

$today_items = $runtime->s2a($module, 'GetTodayReminders', array('user_type' => 'U', 'user_id' => $r['userID']));
if (count($today_items) > 0) {
  $pageParams['todayitems'] = $today_items; 
  $pageParams['today'] = $runtime->doTemplate($module, 'planner.today', $pageParams);
} else {
  $pageParams['today'] = $runtime->doTemplate($module, 'planner.today.empty');
}

$overdue_items = $runtime->s2a($module, 'GetOverdueReminders', array('user_type' => 'U', 'user_id' => $r['userID']));
if (count($overdue_items) > 0) {
  #print Dumper($overdue_items);
  $pageParams['overdueitems'] = $overdue_items;
  $pageParams['overdue'] = $runtime->doTemplate($module, 'planner.overdue', $pageParams);
} else {
  $pageParams['overdue'] = $runtime->doTemplate($module, 'planner.overdue.empty');
} 

$future_items = $runtime->s2a($module, 'GetFutureReminders', array('user_type' => 'U', 'user_id' => $r['userID']));
if (count($future_items) > 0) {
  $pageParams['futureitems'] = $future_items; 
  $pageParams['future'] = $runtime->doTemplate($module, 'planner.future', $pageParams);
} else {
  $pageParams['future'] = $runtime->doTemplate($module, 'planner.future.empty');
}

$pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'title.planner', $pageParams);
$page->add('main', $runtime->doTemplate($module, 'planner', $pageParams);


srun('main', 'RegisterPageview', array('entity_type' => 'dashboard.planner', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

?>
