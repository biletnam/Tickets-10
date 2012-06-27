<?php

$op = lavnn('op', $_REQUEST, '');
$ids = array();
$project = lavnn('project');
$projectInfo = array($project <> '') ? s2r($module, 'GetProjectInfo', array('id' => $project)) : ();
$handler = lavnn('handler');
$handlerInfo = array($handler <> '') ? s2r($module, 'GetEmployeeInfo', array('id' => $handler)) : ();
$priority = lavnn('priority');

while (($request_key, $request_value) = each $_REQUEST) {
  my($prefix, $suffix) = split('_', $request_key);
  if ($prefix == 'id' && $suffix <> '') {
    push @ids, $suffix;
  }
}

if (count($ids) > 0 && $op <> '') {
  $historyids = array();
  if ($op == 'markcancelled') {
    foreach $id (@ids) {
      $runtime->db->sqlrun($module, 'SetTicketStatus', array('id' => $id, 'status' => 'CLD'));
      $sqlParams = array('ticket' => $id, 'editor' => $r['userInfo']['staff_id'], 'new_status' => 'CLD');
      $sqlParams['explained'] = $objT->explain_history_item($sqlParams);
      $historyids{$id} = $runtime->sid($module, 'AddTicketHistory', $sqlParams);
    }
    $_SESSION['flash'] = count($ids)." ticket(s) marked as Cancelled");
  } elseif ($op == 'markfixed') {
    foreach $id (@ids) {
      $runtime->db->sqlrun($module, 'SetTicketStatus', array('id' => $id, 'status' => 'FIX'));
      $sqlParams = array('ticket' => $id, 'editor' => $r['userInfo']['staff_id'], 'new_status' => 'FIX');
      $sqlParams['explained'] = $objT->explain_history_item($sqlParams);
      $historyids{$id} = $runtime->sid($module, 'AddTicketHistory', $sqlParams);
    }
    $_SESSION['flash'] = count($ids)." ticket(s) marked as Fixed");
  } elseif ($op == 'close') {
    foreach $id (@ids) {
      $runtime->db->sqlrun($module, 'SetTicketStatus', array('id' => $id, 'status' => 'CLO'));
      $sqlParams = array('ticket' => $id, 'editor' => $r['userInfo']['staff_id'], 'new_status' => 'CLO');
      $sqlParams['explained'] = $objT->explain_history_item($sqlParams);
      $historyids{$id} = $runtime->sid($module, 'AddTicketHistory', $sqlParams);
    }
    $_SESSION['flash'] = count($ids)." ticket(s) marked as Closed");
  } elseif ($op == 'setpriority' && $priority <> '') {
    foreach $id (@ids) {
      $runtime->db->sqlrun($module, 'SetTicketPriority', array('id' => $id, 'priority' => $priority));
      $sqlParams = array('ticket' => $id, 'editor' => $r['userInfo']['staff_id'], 'new_priority' => $priority);
      $sqlParams['explained'] = $objT->explain_history_item($sqlParams);
      $historyids{$id} = $runtime->sid($module, 'AddTicketHistory', $sqlParams);
    }
    $_SESSION['flash'] = count($ids)." ticket(s) got a new priority");    
  } elseif ($op == 'add2proj' && count($projectInfo) > 0) {
    #TODO check if project is valid and get its moderator to set as new_reviewer
    foreach $id (@ids) {
      $runtime->db->sqlrun($module, 'SetTicketProject', array('id' => $id, 'project' => $project));
      $sqlParams = array('ticket' => $id, 'editor' => $r['userInfo']['staff_id'], 'new_project' => $project);
      $sqlParams['explained'] = $objT->explain_history_item($sqlParams);
      $historyids{$id} = $runtime->sid($module, 'AddTicketHistory', $sqlParams);
    }
    $_SESSION['flash'] = count($ids)." ticket(s) included to project ".$projectInfo['title']);    
  } elseif ($op == 'sethandler' && count($handlerInfo) > 0) {
    foreach $id (@ids) {
      $runtime->db->sqlrun($module, 'SetTicketHandler', array('id' => $id, 'handler' => $handler));
      $sqlParams = array('ticket' => $id, 'editor' => $r['userInfo']['staff_id'], 'new_handler' => $handler);
      $sqlParams['explained'] = $objT->explain_history_item($sqlParams);
      $historyids{$id} = $runtime->sid($module, 'AddTicketHistory', $sqlParams);
    }
    $_SESSION['flash'] = count($ids)." ticket(s) assigned to ".$handlerInfo['strFullName']);    
  }
  while (($ticketid, $historyid) = each %historyids) {
    $historyitem = $runtime->s2r($module, 'GetTicketHistory', array('id' => $ticketid, 'historyid' => $historyid)); 
    if (count($historyitem) > 0) {
      $historyitem['_subject_'] = $r->txt->do_template($module, 'tickethistory.subject', $historyitem);
      $objT->mail_ticket_action($ticketid, $historyitem); # TODO Make sure this method is not really obsolete, as it does not use $objNotification
    }  
  }
} else {
    $_SESSION['error'] = "Select some items in order to run multiple operation");
}

go(lavnn('nextUrl'));

?>
