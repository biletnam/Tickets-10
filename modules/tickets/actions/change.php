<?php

use CFileUploader;
use DateChemistry;
use objNotification;

our $fu = new CFileUploader($r);

$sqlParams = array();
$flash = array();

$ticket_id = lavnn('ticket', $_REQUEST, '');
$user_id = $_REQUEST['user_id'] = $_REQUEST['editor'] = $r['userInfo']['staff_id'];

$ticketInfo = $runtime->s2r($module, 'GetTicketInfo', array('id' => $ticket_id));
if (count($ticketInfo) > 0) {
  $op = lavnn('op');
  $new_status = ''; 
  $commentid = ''; $attachment_id = ''; $timereport_id = '';
  $ignoreChanges = 0; $oldTicketInfo = %ticketInfo;

  # Some shortcut boolean variables  
  $isCreator = $ticketInfo['creator'] == $r['userInfo']['staff_id'];
  $isReviewer = $ticketInfo['reviewer'] == $r['userInfo']['staff_id'];
  $isHandler = $ticketInfo['handler'] == $r['userInfo']['staff_id'];
  $privateTicket = array($isCreator && $isReviewer && $isHandler);
  
  # Get old values from ticket info
  $old_status = $ticketInfo['status'];
  $old_project = $ticketInfo['project'];
  $old_reviewer = $ticketInfo['reviewer'] || '';
  $old_handler = $ticketInfo['handler'] || '';
  $runtime->saveMoment('  old values are fetched');

  # check if ticket should be taken over by superadmin
  $new_creator = '';
  if ($op == 'takeover') {
    $new_creator = $r['userInfo']['staff_id'];
    $runtime->db->sqlrun($module, 'SetTicketCreator', array('id' => $ticket_id, 'creator' => $new_creator));
    push @flash, 'Ticket taken over by superadmin';    
  }
  
  # check if ticket duplication option was selected
  if ($op == 'clone') {
    $new_ticket_id = sid($module, 'CloneTicket', $ticketInfo);
    if ($new_ticket_id > 0) {
      %ticketInfo = $runtime->s2r($module, 'GetTicketInfo', array('id' => $new_ticket_id));
      $ticketInfo['oldid'] = $ticket_id;
      $ticket_id = $new_ticket_id;
      $comment_id = sid($module, 'AddTicketCloneComment', $ticketInfo);
      push @flash, 'Ticket cloned';
    } else {
      set_cookie('error', 'Ticket cloning failed. Changes ignored.');
      $ignoreChanges = 1;
    }
  } 
  $runtime->saveMoment('  cloning option examined');
  
  # get handler and project just in case if they should change during processing status   
  $new_handler = '';
  $new_project = '';

  # Now let's see if status has changed
  if ($op == 'close') { 
    $new_status = 'CLO';
  } elseif ($op == 'send2handler') {  
    $new_status = 'OPN'; 
    #also, set the new handler
    $new_handler = lavnn('handler', $_REQUEST, '');
    if ($new_handler == '') {
      set_cookie('error', 'Handler should be set!');
      $new_status = $old_status;
      $new_handler = '';
    }    
  } elseif ($op == 'send2project') {  
    $new_status = 'NEW'; 
    #also, set the new project
    $new_project = lavnn('project', $_REQUEST, '');
    $projectInfo = $runtime->s2r($module, 'GetProjectInfo', array('id' => $new_project));
    if (count($projectInfo) > 0) {
      $new_reviewer = $projectInfo['moderator'];
    } else {
      set_cookie('error', 'Valid project should be set!');
      $new_status = $old_status;
      $new_project = '';
    }
  } elseif ($op == 'back2handler') {  
    $new_status = 'OPN';
  } elseif ($op == 'back2reviewer') {  
    $new_status = 'RJC';
  } elseif ($op == 'markfixed') {  
    $new_status = 'FIX';
  } elseif ($op == 'markcancelled') {  
    $new_status = 'CLD';
  } elseif ($op == 'send2test') {  
    $new_status = 'RDY';
  } elseif ($op == 'reject') {  
    $new_status = 'RJC';
  } elseif ($op == 'reopen') {  
    $new_status = 'OPN';
  } elseif ($op == 'delete') {  
    go("?f=tickets/deleteticket&id=$ticket_id");
  }   
  $runtime->saveMoment('  new status calculated');

  # Check if new handler should be set
  if ($new_handler <> '' && $new_handler <> $old_handler && !$ignoreChanges) {
    $runtime->db->sqlrun($module, 'SetTicketHandler', array('id' => $ticket_id, 'handler' => $new_handler));
    push @flash, 'Ticket assigned to another person';
  } else {
    $new_handler = '';
  }
  $runtime->saveMoment('  new handler checked');

  # Check if new project should be set
  if ($new_project <> '' && $new_project <> $old_project && $new_reviewer <> '' && !$ignoreChanges) {
    $runtime->db->sqlrun($module, 'SetTicketProject', array('id' => $ticket_id, 'project' => $new_project, 'reviewer' => $new_reviewer));
    push @flash, 'Ticket moved to another project';
  } else {
    $new_project = '';
  }
  $runtime->saveMoment('  new project checked');
  
  # Set new status if required
  if ($new_status <> '' && $new_status <> $old_status && !$ignoreChanges) {
    $runtime->db->sqlrun($module, 'SetTicketStatus', array('id' => $ticket_id, 'status' => $new_status));
    push @flash, 'Status of ticket is changed';
  } else {
    $new_status = '';
  }
  $runtime->saveMoment('  new status checked');

  $recalculateWeight = 0;

  # Set new priority if changed
  $old_priority = $ticketInfo['priority'] || '';
  $new_priority = lavnn('priority', $_REQUEST, '');
  if ($new_priority <> '' && $new_priority <> $old_priority && !$ignoreChanges) {
    $runtime->db->sqlrun($module, 'SetTicketPriority', array('id' => $ticket_id, 'priority' => $new_priority));
    $recalculateWeight = 1;
    push @flash, 'Ticket priority is changed';
  } else {
    $new_priority = '';
  }
  $runtime->saveMoment('  new priority checked');

  # Change due date if requested
  $old_duedate = $ticketInfo['duedate'] || '';
  $new_duedate = $_REQUEST['duedate'] || '';
  if ($new_duedate <> '' && !$ignoreChanges) {
    $old_duedate = substr($old_duedate, 0, 19);
    $new_duedate = DateChemistry::fulldate2mysql($new_duedate);
    if ($old_duedate <> $new_duedate) {
      $runtime->db->sqlrun($module, 'SetTicketDueDate', array('id' => $ticket_id, 'duedate' => $new_duedate));
      $recalculateWeight = 1;
      push @flash, "Ticket due date is changed";
    } else {
      $new_duedate = '';
    }
  } 
  $runtime->saveMoment('  new duedate checked');

  # Change title and contents if requested
  $old_title = $ticketInfo['title'] || '';
  $new_title = $_REQUEST['title'] || '';
  if ($new_title <> '' && $old_title <> $new_title && !$ignoreChanges && ($new_ticket_id == '' || $new_ticket_id <> '' && $new_title <> $oldTicketInfo['title'])) {
    $runtime->db->sqlrun($module, 'SetTicketTitle', array('id' => $ticket_id, 'title' => $new_title));
    push @flash, 'Ticket title is changed';
  } else {
    $new_title = '';
  }
  $runtime->saveMoment('  new title checked');
  $old_contents = $ticketInfo['contents'] || '';
  $new_contents = $_REQUEST['contents'] || '';
  if ($new_contents <> '' && $old_contents <> $new_contents && !$ignoreChanges) {
    $runtime->db->sqlrun($module, 'SetTicketContents', array('id' => $ticket_id, 'contents' => $new_contents));
    push @flash, 'Ticket summary is changed';
  } else {
    $new_contents = '';
  }
  $runtime->saveMoment('  new contents checked');

  # Change planned action date if requested
  $reminderdate = $_REQUEST['actiondate'] || '';
  if ($reminderdate <> '') {
    $remindertext = "Review ticket #$ticket_id, '$new_title'; " . $_REQUEST['reminder'];
    $objT->add_reminder($ticket_id, array(
      'user_type' => 'U', 'user_id' => $r['userID'], 
      'event_date' => $reminderdate, 'event_text' => $_REQUEST['reminder']
    ));
  }
  
  # add comment if any
  $comment = lavnn('comment', $_REQUEST, ''); # TODO Add it to separate table and get id
  if ($comment <> '' && !$ignoreChanges) {
    $comment_id = sid($module, 'AddTicketComment', $_REQUEST);
    push @flash, 'Comment added for the ticket';
  } else {
    $comment_id = '';
  }
  $runtime->saveMoment('  comment checked');
  
  # add time reporting if any
  $reported_hours = lavnn('reported_hours', $_REQUEST, '');  
  $reported_minutes = lavnn('reported_minutes', $_REQUEST, '');  
  $reportedtime_given = array($_REQUEST['reported_hours'].$_REQUEST['reported_minutes'] <> '');
  if ($reportedtime_given && !$ignoreChanges) {
    if (("0$reported_hours" =~ /^-?\d+$/) && ("0$reported_minutes" =~ /^-?\d+$/)) {
      $_REQUEST['total_minutes'] = $total_minutes = $reported_hours * 60 + $reported_minutes;
      $_REQUEST['computed_hours'] = $computed_hours = int($total_minutes / 60); 
      $_REQUEST['computed_minutes'] = $total_minutes - $computed_hours * 60; 
      if ($total_minutes > 0) {
        $timereport_id = sid($module, 'AddTicketTimeReport', $_REQUEST);
        push @flash, 'Time reported for the ticket';
        # If there is another date specified for report, make change in the database
        $reporting_date = lavnn('reporting_date', $_REQUEST, '');
        if ($reporting_date <> '') {
          $runtime->db->sqlrun($module, 'FixTimeReportDate', array('reporting_id' => $timereport_id, 'reporting_date' => $reporting_date));
        }
      }
    } else {
      set_cookie('error', 'Number of reported hours/minutes should be integer');  
    }
  }
  $runtime->saveMoment('  time reportings checked');

  # Change minutes left
  $hours_left = $_REQUEST['timeleft_hours'] || 0;
  $minutes_left = $_REQUEST['timeleft_minutes'] || 0;
  $new_timeleft_given = array($_REQUEST['timeleft_hours'].$_REQUEST['timeleft_hours'] <> '');
  $new_minutesleft = 0;
  if (("0$hours_left" =~ /^-?\d+$/) && ("0$minutes_left" =~ /^-?\d+$/)) {
    $new_minutesleft = $hours_left * 60 + $minutes_left;
    $old_minutesleft = $ticketInfo['minutesleft'] || 0;
    if ($new_timeleft_given && $old_minutesleft <> $new_minutesleft) {
      $runtime->db->sqlrun($module, 'SetTicketTimeLeft', array('id' => $ticket_id, 'minutesleft' => $new_minutesleft));
      push @flash, 'Ticket completion time estimation is changed';
    }
  } else {
    set_cookie('error', 'Number of hours/minutes left should be integer');  
  }
  $runtime->saveMoment('  new time estimation set');

  # add attachment if any
  $attachment_id = 0;
  $attachment_fileid = $fu->uploadfile("attachment");
  if ($attachment_fileid > 0 && !$ignoreChanges) {
    $attachment_id = sid($module, 'AddTicketAttachment', array('ticket' => $ticket_id, 'editor' => $r['userInfo']['staff_id'], 'fileid' => $attachment_fileid));
    if ($attachment_id > 0) {
      push @flash, 'Attachment uploaded for the ticket';
    }
  }
  $runtime->saveMoment('  attachment checked');

  # Recalculate weight if needed - this does not affect manual weight adjustment and vice versa
  if ($recalculateWeight == 1) {
    $runtime->db->sqlrun($module, 'RecalculateTicketWeight', array('id' => $ticket_id));
  }
  $runtime->saveMoment('  new weight is saved for the ticket'); #TODO calculate weight when creating a ticket

  # Refresh textual representation
  $ticketInfo = $runtime->s2r($module, 'GetTicketInfo', array('id' => $ticket_id));
  $ticketInfo['explained'] = $runtime->txt->do_template($module, 'ticket.explain', $ticketInfo);
  $runtime->db->sqlrun($module, 'UpdateTicketExplanation', $ticketInfo);
  $runtime->saveMoment('  ticket explanation updated');

  # if something changed, write to history
  if (count($flash) > 0 && !$ignoreChanges) {
    %sqlParams = array(
      'action' => 'changeticket',
      'ticket' => $ticket_id,
      'editor' => $r['userInfo']['staff_id'],
      'new_creator' => $new_creator,
      'new_reviewer' => $new_reviewer,
      'new_handler' => $new_handler,
      'new_status' => trim($new_status),
      'new_project' => $new_project,
      'new_priority' => $new_priority,
      'new_duedate' => $new_duedate,
      'new_title' => $new_title,
      'new_contents' => $new_contents,
      'comment_id' => $comment_id,
      'attachment_id' => $attachment_id,
      'timereport_id' => $timereport_id
    );
    $sqlParams['new_minutesleft'] = $new_minutesleft if ($new_timeleft_given);
    
    $new_history_id = $objT->create_ticket_history($ticket_id, $sqlParams);
    $runtime->saveMoment('  history item added');
  }
  
  if (count($flash) > 0 && !$ignoreChanges) {
    $_SESSION['flash'] = join(', ', @flash));
  }
}

#uncomment following line to save performance log
$runtime->save_timegauge($db);

if ($ticket_id <> '') {
  go("?p=tickets/viewticket&id=$ticket_id");
} else {
  go('?p=tickets/mytickets');
}

?>
