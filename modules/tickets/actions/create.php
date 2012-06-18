<?php
$newid = $objT->create_ticket(%_REQUEST);
if ($newid > 0) {
  set_cookie('flash', 'Ticket created');
  if ($newid % 1000 == 0) {
    set_cookie('easteregg', "coolticket:$newid");
  }
  go("?p=tickets/viewticket&id=$newid");
} else {
  set_cookie('error', 'Could not create ticket');
  go("?p=tickets/mytickets");
}

?>
