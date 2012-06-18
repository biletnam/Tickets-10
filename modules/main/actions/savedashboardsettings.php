<?php
$_REQUEST['dashboard_articleage'] ||= $_CONFIG['DEFAULT_DASHBOARD_ARTICLEAGE'];
$_REQUEST['dashboard_notificationage'] ||= $_CONFIG['DEFAULT_DASHBOARD_NOTIFICATIONAGE'];
$_REQUEST['user_id'] = $r['userInfo']['staff_id'];
$existingData = $runtime->s2r('main', 'GetUserData', array('id' => $r['userInfo']['staff_id']));
if (count($existingData) > 0) {
  srun('main', 'UpdateDashboardSettings', $_REQUEST);
} else {
  srun('main', 'InsertDashboardSettings', $_REQUEST);
}
set_cookie('flash', 'Dashboard settings saved');

go('?p=main/dashboard&tab=settings');

?>
