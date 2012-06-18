<?php

use objTicketing;
$objT = new objTicketing($r);

$client_id = lavnn('client_id', $_REQUEST, 0);
$ticket_id = lavnn('ticket_id', $_REQUEST, 0);
$op = lavnn('op', $_REQUEST, '');
$sqlParams = array();
if ($client_id > 0) {
  $_REQUEST['user_id'] = $r['userInfo']['staff_id'];
  if ($op <> '') {
    # update ticket tables    
    if ($op == 'reply') {
      if ($ticket_id == 0) {
        $op = ''; # Ticket not given, so we'll add FU comment instead
      }
    } elseif ($op == 'mfee') {
      %sqlParams = (
        'creator' => $r['userInfo']['staff_id'],
        'title' => "Maintenance fee problem for client $client_id",
        'contents' => lavnn('commentary'),
        'target' => "client:$client_id",
        'destination' => 'self',                                      # TODO should be 'project' when there is Kliendihaldur
      );
      $ticket_id = $objT->create_ticket(%sqlParams);
    } elseif ($op == 'cert') {
      %sqlParams = (
        'creator' => $r['userInfo']['staff_id'],
        'title' => "Certificate problem for client $client_id",
        'contents' => lavnn('commentary'),
        'target' => "client:$client_id",
        'destination' => 'self',                                      # TODO should be 'project' when there is Kliendihaldur
      );
      $ticket_id = $objT->create_ticket(%sqlParams);
    } elseif ($op == 'book') {
      %sqlParams = (
        'creator' => $r['userInfo']['staff_id'],
        'title' => "Booking problem for client $client_id",
        'contents' => lavnn('commentary'),
        'target' => "client:$client_id",
        'destination' => 'self',                                      # TODO should be 'project' when there is Kliendihaldur
      );
      $ticket_id = $objT->create_ticket(%sqlParams);
    }
    # Add comment for the ticket
    if ($ticket_id > 0) {
      %sqlParams = ('comment' => lavnn('commentary'));
      $objT->add_comment($ticket_id, $sqlParams);
    }
  } 

  # The following code is not an "else" branch, because $op can change while processing original "if" part  
  if ($op == '') {
    # update follow up tables
    $id = sid($module, 'AddClientComment', $_REQUEST);
    if ($id > 0) {
      set_cookie('flash', 'Comment added');
      $_REQUEST['id'] = $id;
    } else {
      set_cookie('error', 'Could not add comment');
    }
  }
  
  
  go("?p=$module/viewclient&id=$client_id&tab=comments");
} else {
  set_cookie('error', 'Client not found');
  go("?p=$module/search");
}



?>
