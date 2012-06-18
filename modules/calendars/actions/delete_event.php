<?php

use objTicketing;

$nocache = sprintf("%f", time());
$id = lavnn('id', $_REQUEST, '');
$year = lavnn('year', $_REQUEST, '');
$month = lavnn('month', $_REQUEST, '');
if ($id == '' || $year == '' || $month == '') {
  set_cookie('error', 'Please specify calendar event to delete');  
  # do nothing to return empty string
} else {
  # Get calendar id
  $sqlParams = array('id' => $id);
  $eventInfo = $objCal->get_event_info(%sqlParams);
  if (count($eventInfo) > 0) {
    $calendar_id = $eventInfo['calendar'];
    $objCal->delete_event(%sqlParams);
    # Additional after-delete tasks for different types of events
    if ($eventInfo['object_type'] == 'employeeabsence') {
      $ticket_id = $eventInfo['authorization_ticket'];
      if ($ticket_id > 0) {
        $objT = new objTicketing($r);
        $objT->add_comment($ticket_id, array('comment' => dot('absencerequest.ticket.comment.deleted', $eventInfo)));
        $objT->close_ticket($ticket_id);
      }
    }
    go("?i=$module/viewmonth&id=$calendar_id&year=$year&month=$month&nocache=$nocache");
  } else {
    set_cookie('error', 'Event not found');
  }
}

1;

?>
