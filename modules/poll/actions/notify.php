<?php

$poll = lavnn('poll', $_REQUEST, 0);
$ids = join(',', lavnn('ids'), $_REQUEST, '');

if ($poll <> 0) {
  $pollInfo = $runtime->s2r($module, 'GetPollDetails', array('id' => $poll)); 
  if (count($pollInfo) > 0) {
    # Get IDs of all people who have not yet responded to the poll
    if ($ids == '') {
      $respondents = $objP->list_pending_respondents($_REQUEST);
      $ids = join_column(',', 'lngId', $respondents);
    }
    # Create notification and send it
    $subject = $r->txt->do_template($module, 'notification.subject', $pollInfo);
    $digest = $r->txt->do_template($module, 'notification.digest', $pollInfo);
    use objNotification;
    $objN = new objNotification($r);
    $nid = $objN->add_notification('poll', $poll, $subject, $digest);
    $cnt = $objN->add_notification_recipient_employees($nid, $ids);
    $_SESSION['flash'] = 'Notification sent to ' . $cnt . ' persons.')
  } else {
    $_SESSION['error'] = 'Poll not found');
  }
} else {
  $_SESSION['error'] = 'Poll not found');
}
go("?p=$module/list&tab=editlist");
?>
