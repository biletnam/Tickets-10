<?php

$hid = lavnn('hid', $_REQUEST, 0);
if ($hid > 0) {
  $calendarHistoryInfo = $runtime->s2r($module, 'GetCalendarHistoryInfo', array('id' => $hid));
  $user_id = $calendarHistoryInfo['user_id'];
  srun($module, 'DeleteCalendarHistory', array('id' => $hid));
  $nocache = sprintf("%f", time());
  go("?p=$module/employee&id=$user_id&nocache=$nocache&tab=calendarlist");
}
go("?p=staff/search");

?>
