<?php
$_REQUEST['dashboard_articleage'] ||= $_CONFIG['DEFAULT_DASHBOARD_ARTICLEAGE'];
$_REQUEST['dashboard_notificationage'] ||= $_CONFIG['DEFAULT_DASHBOARD_NOTIFICATIONAGE'];
$_REQUEST['user_id'] = $r['userInfo']['staff_id'];
$existingData = $runtime->s2r('main', 'GetUserData', array('id' => $r['userInfo']['staff_id']));
if (count($existingData) > 0) {
  $runtime->db->sqlrun($module, 'UpdateSettings', $_REQUEST);
} else {
  $runtime->db->sqlrun($module, 'InsertSettings', $_REQUEST);
}
$_SESSION['flash'] = 'Dashboard settings saved');
go("?p=$module/notifications");

?>
