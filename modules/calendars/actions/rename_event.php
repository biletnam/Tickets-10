<?php

$nocache = sprintf("%f", time());
$id = lavnn('id', $_REQUEST, '');
$year = lavnn('year', $_REQUEST, '');
$month = lavnn('month', $_REQUEST, '');
if ($id == '' || $year == '' || $month == '') {
  $_SESSION['error'] = 'Please provide calendar event and new description to rename the event');  
  # do nothing to return empty string
} else {
  $comment = lavnn('comment');
  if ($comment == '') {
    $_SESSION['error'] = 'Please provide new comment to rename event');  
  } else {
    $sqlParams = array('id' => $id, 'comment' => $comment);
    $eventInfo = $objCal->get_event_info(%sqlParams);
    if (count($eventInfo) > 0) {
      $calendar_id = $eventInfo['calendar'];
      $objCal->rename_event(%sqlParams);
      go("?i=$module/viewmonth&id=$calendar_id&year=$year&month=$month&nocache=$nocache");
    }
  }
}

1;

?>
