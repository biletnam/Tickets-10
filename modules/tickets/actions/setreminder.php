<?php
$id = lavnn('ticket');
$url = "?p=$module/mytickets";
if ($id <> '') {
  $ticketInfo = $runtime->s2r($module, 'GetTicketInfo', array('id' => $id)); 
  if (count($ticketInfo) > 0) {
    while (($request_key, $request_value) = each $_REQUEST) {
      my($prefix, $suffix) = split('_', $request_key);
      if ($prefix == 'id' && $suffix <> '') {
        $objT->add_reminder($id, array('user_id' => $suffix, 'event_text' => $_REQUEST['event_text'], 'event_date' => $_REQUEST['event_date']));
      }
    } 
    $url = "?p=$module/viewticket&id=$id";
  } else {
    $_SESSION['error'] = 'Requested ticket was not found');
  }
} else {
  $_SESSION['error'] = 'No ticket to work with');
}
go($url);

?>
