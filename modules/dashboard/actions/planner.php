<?php

use Calendar;
use DateChemistry;
$today = Calendar::getTodayArr();
$pageParams = array();

$today_items = $runtime->s2a($module, 'GetTodayReminders', array('user_type' => 'U', 'user_id' => $r['userID']));
if (count($today_items) > 0) {
  $pageParams['todayitems'] = $today_items; 
  $pageParams['today'] = $runtime->txt->do_template($module, 'planner.today', $pageParams);
} else {
  $pageParams['today'] = $runtime->txt->do_template($module, 'planner.today.empty');
}

$overdue_items = $runtime->s2a($module, 'GetOverdueReminders', array('user_type' => 'U', 'user_id' => $r['userID']));
if (count($overdue_items) > 0) {
  #print Dumper($overdue_items);
  $pageParams['overdueitems'] = $overdue_items;
  $pageParams['overdue'] = $runtime->txt->do_template($module, 'planner.overdue', $pageParams);
} else {
  $pageParams['overdue'] = $runtime->txt->do_template($module, 'planner.overdue.empty');
} 

$future_items = $runtime->s2a($module, 'GetFutureReminders', array('user_type' => 'U', 'user_id' => $r['userID']));
if (count($future_items) > 0) {
  $pageParams['futureitems'] = $future_items; 
  $pageParams['future'] = $runtime->txt->do_template($module, 'planner.future', $pageParams);
} else {
  $pageParams['future'] = $runtime->txt->do_template($module, 'planner.future.empty');
}

$pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'title.planner', $pageParams);
$page->add('main', $runtime->txt->do_template($module, 'planner', $pageParams);


$runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'dashboard.planner', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

?>
