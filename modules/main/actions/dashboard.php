<?php

use ctlTab;
use objNotification;
use Calendar;
use DateChemistry;

%pageParams = array();

$settingOptions = $r['userInfo']['additionalData'];
$defaultview = $settingOptions['dashboard_defaultview'];
$defaultviewoptions = $runtime->getSortedDictArr('main', 'dashboard.tabs', $defaultview);
$isexternal = $settingOptions['dashboard_isexternal'];
if ($isexternal == "") {
  $isexternal = array($r['userInfo']['lngWorkPlace'] == -1234) ? "0" : "1";
} 
$locationoptions = $runtime->getSortedDictArr('main', 'officelocation', $isexternal);
$settingOptions['dashboard_defaultview'] ||= $_CONFIG['DEFAULT_DASHBOARD_DEFAULTVIEW'];
$settingOptions['dashboard_articleage'] ||= $_CONFIG['DEFAULT_DASHBOARD_ARTICLEAGE'];
$settingOptions['dashboard_notificationage'] ||= $_CONFIG['DEFAULT_DASHBOARD_NOTIFICATIONAGE'];
$settingOptions['defaultviewoptions'] = $defaultviewoptions;
$settingOptions['locationoptions'] = $locationoptions;
$runtime->saveMoment('  read dashboard settings');

$notification_age = $settingOptions['dashboard_notificationage'];
$pageParams['notification_age'] = $notification_age;

$objN = new objNotification($r);
$nn = $objN->list($r['userInfo']['staff_id'], $notification_age);
$runtime->saveMoment('  fetched notifications from db');
$notifications = array(); $notification_cnt = 0;
foreach $eD (reverse sort keys %nn) {
  $n = $nn{$eD];
  $notification_cnt += count($n);
#  print $eD, Dumper($n);
  $nhash = array('notifications' => loopt('dashboard.notification', @n));
  if ($n[0]['daysdiff'] == 0) {
    $nhash['day'] = $runtime->txt->do_template($module, 'dashboard.notification.today');
  } elseif ($n[0]['daysdiff'] == 1) {
    $nhash['day'] = $runtime->txt->do_template($module, 'dashboard.notification.yesterday');
  } else {
    $nhash['day'] = $runtime->txt->do_template($module, 'dashboard.notification.day', ${$n[0]});
  }
  push @notifications, dot('dashboard.notification.list', $nhash);
}
$pageParams['notifications'] = join('', @notifications);
$pageParams['notifications_cnt'] = $notification_cnt;
$runtime->saveMoment('  prepared notifications tab');

# Create a dashboard view tab
$tabDashboard = new ctlTab($r, "tcDashboard");
$tabDashboard->addTab('notifications', dot('dashboard.notifications.tabheader', $pageParams), dot('dashboard.notifications', $pageParams)) if $notification_cnt > 0; 
$tabDashboard->addTab('planner', dot('dashboard.planner.tabheader'), dot('dashboard.wait.planner')); 
$tabDashboard->addTab('docs', dot('dashboard.phdocs.tabheader'), dot('dashboard.wait.phdocs')); 
$tabDashboard->addTab('settings', dot('dashboard.settings.tabheader'), dot('dashboard.settings', $settingOptions), 'right'); 
$tabDashboard->setDefaultTab(lavnn('tab') || 'notifications');
$pageParams['tabcontrol'] = $tabDashboard->getHTML(); 
$runtime->saveMoment(' rendered tab control');

$page['js'] = dotmod('main', 'tabcontrol.js');
$page['css'] = dotmod('main', 'tabcontrol.css');
$page['js'] .= dotmod('main', 'notifications.js');
$page->add('css',  dotmod('main', 'notifications.css');
$runtime->saveMoment(' included custom JavaScript and CSS');

$pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'title.dashboard');
$page->add('main', $runtime->txt->do_template('main', 'dashboard', $pageParams);
$runtime->saveMoment(' rendered main part of the page');



# register pageview
srun('main', 'RegisterPageview', array('entity_type' => 'dashboard', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

?>
