<?php

use Calendar;
use objNotification;

$objN = new objNotification($r);
$today = Calendar::getTodayArr();
$_REQUEST['date_from'] ||= Calendar::addDate($today['fulldate'], -90); 
$_REQUEST['date_to'] ||= $today['fulldate']; 
$pageParams['date_from'] = $_REQUEST['date_from'];
$pageParams['date_to'] = $_REQUEST['date_to']; 
$nn = $objN->search(%_REQUEST); 
$runtime->saveMoment('  fetched notifications from db');

$notifications = array(); $notification_cnt = 0;
foreach $eD (reverse sort keys %nn) {
  $n = $nn{$eD];
  $notification_cnt += count($n);
  $nhash = array('notifications' => loopt('notifications.listitem', @n));
  $nhash['day'] = $runtime->txt->do_template($module, 'notifications.day.withyear', ${$n[0]});
  push @notifications, dot('notifications.list', $nhash);
}
$pageParams['notifications'] = join('', @notifications) || dot('searchnotifications.none');
$pageParams['notifications_cnt'] = $notification_cnt;
$runtime->saveMoment(' processed retrieved list of notifications');

$page['js'] .= dotmod('main', 'notifications.js');
$page->add('css',  dotmod('main', 'notifications.css');
$runtime->saveMoment(' included custom JavaScript and CSS');

$pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'title.searchnotifications');
$page->add('main', $runtime->txt->do_template($module, 'searchnotifications', $pageParams);


srun('main', 'RegisterPageview', array('entity_type' => 'dashboard.searchnotifications', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

?>
