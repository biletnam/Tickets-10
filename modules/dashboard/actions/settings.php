<?php

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

$settingOptions['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'title.settings', $settingOptions);
$page->add('main', $runtime->txt->do_template($module, 'settings', $settingOptions);


$runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'dashboard.settings', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));
?>
