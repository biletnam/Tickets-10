<?php

use objTicketing;
$objT = new objTicketing($r);

$gen_id = lavnn('generator_id', $_REQUEST, 0);
$ticket_id = lavnn('ticket_id', $_REQUEST, 0);
$commentary = lavnn('commentary') || '',
$op = lavnn('op', $_REQUEST, '');
$sqlParams = array();
if ($gen_id > 0) {
  $_REQUEST['user_id'] = $r['userInfo']['staff_id'];
  if ($op <> '') {
    # update ticket tables    
    if ($op == 'reply') {
      if ($ticket_id == 0) {
        $op = ''; # Ticket not given, so we'll add usul generator comment instead
      }
    } elseif (substr($op, 0, 8) == 'project_') { 
      %sqlParams = array(
        'creator' => $r['userInfo']['staff_id'],
        'title' => ($r['txt']words_shortener($commentary, 5, 1) || "Automated ticket for generator $generator_id"),
        'contents' => $commentary,
        'target' => "generator:$gen_id",
        'destination' => 'project',                                      
        'project' => substr($op, 8),
      );
      $ticket_id = $objT->create_ticket(%sqlParams);
    }
    # Add comment for the ticket
    if ($ticket_id > 0) {
      %sqlParams = array('comment' => $commentary);
      $objT->add_comment($ticket_id, $sqlParams);
    }
  } 

  # The following code is not an "else" branch, because $op can change while processing original "if" part  
  if ($op == '') {
    $sqlParams = array('id' => $gen_id, 'editor' => $r['userID'], 'comment' => $commentary);
    $id = $runtime->sid($module, 'AddGeneratorComment', $sqlParams);
    if ($id > 0) {
      $_SESSION['flash'] = 'Comment added');
      $_REQUEST['id'] = $id;
    } else {
      $_SESSION['error'] = 'Could not add comment');
    }
  }
  
  
  go("?p=$module/view&id=$gen_id&tab=comments");  
} else {
  $_SESSION['error'] = 'Generator not found');
  go("?p=$module/search");
}



?>
