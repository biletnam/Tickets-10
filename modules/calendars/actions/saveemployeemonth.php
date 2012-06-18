<?php

$nocache = sprintf("%f", time());
$user_id = lavnn('user_id', $_REQUEST, 0);
if ($user_id > 0) {
  if (lavnn('day_type') == '' || lavnn('day_qty') == '' || lavnn('comment') == '' || lavnn('collected_days') == '') {
    set_cookie('error', 'Please select days and specify day type, quantity and comment')
  } else {
    $sqlParams = %_REQUEST;
    foreach $day (split(',', lavnn('collected_days'))) {
      $sqlParams['qty'] = array(lavnn('day_qty') == 'half' ? 0.5 : 1);
      $sqlParams['day'] = $day;
      srun($module, 'AddUserAbsence', $sqlParams);
    }
    set_cookie('flash', 'Employee calendar changed');
  }
  $year = lavnn('year');
  $month = lavnn('month');
  go("?p=$module/employee&id=$user_id&year=$year&month=$month&nocache=$nocache");
} else {
  go('');
}

?>
