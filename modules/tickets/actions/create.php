<?php
$newid = $objT->create_ticket($_REQUEST);
if ($newid > 0) {
  $_SESSION['flash'] = 'Ticket created');
  if ($newid % 1000 == 0) {
    set_cookie('easteregg', "coolticket:$newid");
  }
  go("?p=tickets/viewticket&id=$newid");
} else {
  $_SESSION['error'] = 'Could not create ticket');
  go("?p=tickets/mytickets");
}

?>
