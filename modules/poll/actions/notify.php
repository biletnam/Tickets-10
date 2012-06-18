<?php

$poll = lavnn('poll', $_REQUEST, 0);
$ids = join(',', lavnn('ids'), $_REQUEST, '');

if ($poll <> 0) {
  $pollInfo = $runtime->s2r($module, 'GetPollDetails', array('id' => $poll)); 
  if (count($pollInfo) > 0) {
    # Get IDs of all people who have not yet responded to the poll
    if ($ids == '') {
      $respondents = $objP->list_pending_respondents(%_REQUEST);
      $ids = join_column(',', 'lngId', $respondents);
    }
    # Create notification and send it
    $subject = $runtime->txt->do_template($module, 'notification.subject', $pollInfo);
    $digest = $runtime->txt->do_template($module, 'notification.digest', $pollInfo);
    use objNotification;
    $objN = new objNotification($r);
    $nid = $objN->add_notification('poll', $poll, $subject, $digest);
    $cnt = $objN->add_notification_recipient_employees($nid, $ids);
    set_cookie('flash', 'Notification sent to ' . $cnt . ' persons.')
  } else {
    set_cookie('error', 'Poll not found');
  }
} else {
  set_cookie('error', 'Poll not found');
}
go("?p=$module/list&tab=editlist");
?>
