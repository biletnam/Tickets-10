<?php

$type = lavnn('type', $_REQUEST, '');
$returnUrl = lavnn('returnUrl');
if ($type == 'employee_request_email_address') {
  $ticket = $objSW->do_employee_request_email_address($_REQUEST);
  if ($ticket > 0) {
    $_SESSION['flash'] = "Email address request sent. Refer to ticket #$ticket");
  } else {
    $_SESSION['error'] = "Email address request not sent. ");
  }
} elseif($type == 'employee_request_web_access') { 
  $ticket = $objSW->do_employee_request_web_access($_REQUEST);
  if ($ticket > 0) {
    $_SESSION['flash'] = "Web access request sent. Refer to ticket #$ticket");
  } else {
    $_SESSION['error'] = "Web access request not sent. ");
  }
} elseif($type == 'employee_request_merlin_access') { 
  $ticket = $objSW->do_employee_request_merlin_access($_REQUEST);
  if ($ticket > 0) {
    $_SESSION['flash'] = "Merlin access request sent. Refer to ticket #$ticket");
  } else {
    $_SESSION['error'] = "Merlin access request not sent. ");
  }
} elseif($type == 'employee_request_comm_query') { 
  $ticket = $objSW->do_employee_request_comm_query($_REQUEST);
  if ($ticket > 0) {
    $_SESSION['flash'] = "Commission query request sent. Refer to ticket #$ticket");
  } else {
    $_SESSION['error'] = "Commission query request not sent. ");
  }
} else {
  $_SESSION['error'] = "Unknown workflow type called: $type");
}

go('?' . $returnUrl);
?>
