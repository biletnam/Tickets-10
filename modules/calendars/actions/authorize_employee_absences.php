<?php

$calendar_id = $_REQUEST['yearview_calendar_id'];
if ($calendar_id > 0) {
  $ids = lavnn('ids'); 
  if (count($ids) > 0) {
    use objCalendar;
    use objTicketing;
    use objStaffManagement;
    $objCal = new objCalendar($r);
    $objT = new objTicketing($r);
    $objSM = new objStaffManagement($r);
  
    # Get calendar info, related employee and his line manager and HR staff in employee's office
    $calendarInfo = $objCal->get_calendar_info(('id' => $calendar_id));
    $employee = $calendarInfo['object_id'];
    $employeeInfo = $objSM->get_user_info($employee); 
    $employeeSpecialData = $objSM->get_user_specialdata($employee);
    $linemanager = $employeeSpecialData['line_manager'] || 0;
    if ($linemanager == $r['userID']) {
      $comment = lavnn('authorizeabsence_comment', $_REQUEST, '');
      $tickets = array();
      # Authorize or Decline events one by one
      foreach $id (@ids) {
        $eventInfo = $objCal->get_event_info(('id' => $id)); 
        $answer = lavnn("authresult_$id");
        if (count($eventInfo) > 0 && $answer <> '') {
          $ticket_id = $eventInfo['authorization_ticket'] || 0;
          if (! exists $tickets{$ticket_id}) {
            $tickets{$ticket_id} = hash2ref('approved' => 0, 'declined' => 0, 'details' =>'');
          }
          if ($answer == 'approve') {
            $objCal->approve_event(('id' => $id, 'editor' => $r['userID']));
            $tickets{$ticket_id}['approved'] += 1;
            $tickets{$ticket_id}['details'] .= $runtime->txt->do_template($module, 'absencerequest.ticket.comment.approved', $eventInfo);
          } elseif ($answer == 'decline') {
            $objCal->decline_event(('id' => $id, 'editor' => $r['userID']));
            $tickets{$ticket_id}['declined'] += 1;
            $tickets{$ticket_id}['details'] .= $runtime->txt->do_template($module, 'absencerequest.ticket.comment.declined', $eventInfo);
          }
        }
      }
      # Now, test if all requested absences are authorized in all the tickets. 
      foreach $ticket_id (keys %tickets) {
        if ($ticket_id > 0) {
          $standingEvents = $objCal->check_ticket_events(('id' => $ticket_id));
          $authResults = $tickets{$ticket_id];
          $authResults['comment'] = $comment;
          $objT->add_comment($ticket_id, array('comment' => dot('absencerequest.ticket.comment', $authResults)));
          if (count($standingEvents) == 0) { # no more events
            $objT->close_ticket($ticket_id);
          }
        }
      }
      $_SESSION['flash'] = 'Authorization of absence request(s) succeeded');
      go("?p=$module/view&id=$calendar_id&tab=year");
    } else {
      set_cookie('error', 'Authorization of employee absence request is only allowed to line manager of employee');
      go("?p=$module/view&id=$calendar_id&tab=year");
    }
  } else {
    set_cookie('error', 'Please select some items in order to authorize them');
    go("?p=$module/view&id=$calendar_id&tab=year");
  }
} else {
  # If calendar was not found, go to MyCalendars page
  go("?p=$module/mycalendars");
}

?>
