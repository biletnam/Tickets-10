<?php

use objNotification;

$settingOptions = $r['userInfo']['additionalData'];
$notification_age = $settingOptions['dashboard_notificationage'] || 5;
$pageParams['notification_age'] = $notification_age;

$objN = new objNotification($r);
$nn = $objN->list($r['userInfo']['staff_id'], $notification_age); 
$runtime->saveMoment('  fetched notifications from db');

$notifications = array(); $notification_cnt = 0;
foreach $eD (reverse sort keys %nn) {
  $n = $nn{$eD];
  $notification_cnt += count($n);
  $nhash = array('notifications' => loopt('notifications.listitem', @n));
  if ($n[0]['daysdiff'] == 0) {
    $nhash['day'] = $r->txt->do_template($module, 'notifications.today');
  } elseif ($n[0]['daysdiff'] == 1) {
    $nhash['day'] = $r->txt->do_template($module, 'notifications.yesterday');
  } else {
    $nhash['day'] = $r->txt->do_template($module, 'notifications.day', ${$n[0]});
  }
  push @notifications, dot('notifications.list', $nhash);
}
$pageParams['notifications'] = join('', $notifications);
$pageParams['notifications_cnt'] = $notification_cnt;
$pageParams['didyouknow'] = $r->txt->do_template($module, 'notifications.didyouknow') if $notification_cnt > 1;
$runtime->saveMoment(' processed retrieved list of notifications');

$page['js'] .= $r->txt->do_template('main', 'notifications.js');
$page->add('css',  $r->txt->do_template('main', 'notifications.css');
$runtime->saveMoment(' included custom JavaScript and CSS');

$pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'title.notifications', $pageParams);
$page->add('main', $r->txt->do_template($module, 'notifications', $pageParams);


$runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'dashboard.notifications', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

?>
