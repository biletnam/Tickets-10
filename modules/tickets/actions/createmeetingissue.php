<?php
$id = lavnn('meeting');
if ($id > 0) {
  use objTicketing;
  $objT = new objTicketing($r);
  $creator = lavnn('creator');
  $destination = ($creator == '') ? 'none' : 'self';
  $sqlParams = (
    'creator' => $creator,
    'destination' => $destination,
    'title' => lavnn('title'),
    'contents' => "[meeting #$id] " . lavnn('contents'),
    'priority' => lavnn('priority'),
    'duedate' => lavnn('duedate'),
    'target' => "meeting:$id"
  );

  $newid = $objT->create_ticket(%sqlParams);
  if ($newid > 0) {
    $runtime->set_cookie('flash', 'Issue is saved');
    $notified = lavnn('notified');
    foreach $np (@notified) {
      $objT->add_notification_recipient($newid, array('staff_id' => $np));
    }
    $revision = lavnn('revision', $_REQUEST, '');
    if ($revision <> '') {
      $objT->add_reminder($newid, array('user_type' => 'U', 'user_id' => lavnn('creator'), 'event_date' => $revision));
      foreach $np (@notified) {
        $objT->add_reminder($newid, array('user_type' => 'U', 'user_id' => $np, 'event_date' => $revision));
      }
    }
  } else {
    $runtime->set_cookie('error', 'Could not save the issue!');
  }  
  go("?p=$module/viewmeeting&id=$id&tab=issues");
} else {
  go("?p=$module/meetings");
}

?>
