<?php
$pageletParams = array();
$_REQUEST['user_id'] = lavnn('user') || $r['userID'];
$timereports = $runtime->s2a($module, 'GetUserReportingFullMonth', array($_REQUEST));
$pageletParams['timereports'] = $timereports;
print $runtime->txt->do_template($module, 'usertimearchive', $pageletParams);
?>
