<?php

$id = lavnn('id', $_REQUEST, '');
$returnto = lavnn('returnto');
$url = "?p=$module/mytickets";
if ($id <> '') {
  $ticketInfo = $runtime->s2r($module, 'GetTicketInfo', $_REQUEST); 
  if (count($ticketInfo) > 0) {
    if ($ticketInfo['creator'] == $r['userID']) {
      $objT->delete_ticket($id);
      set_cookie('flash', "Ticket '".$ticketInfo['title']."' deleted");
      if ($returnto == 'project') {
        $pid = $ticketInfo['project'];
        $url = "?p=$module/project&id=$pid";
      }
    } else {
      set_cookie('error', 'Only creator can delete the ticket');
    }
  } else {
    set_cookie('error', 'Requested ticket was not found');
  }
} else {
  set_cookie('error', 'Choose ticket to delete');
}
go($url);

?>
