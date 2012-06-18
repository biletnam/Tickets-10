<?php
$_REQUEST['creator'] = $r['userID'];
$id = sid($module, 'CreateMeeting', $_REQUEST);
if ($id > 0) {
  $originalmeeting = lavnn('originalmeeting');
  if ($originalmeeting <> '') { # clone
    srun($module, 'CopyMeetingIssues', array('copyfrom' => lavnn('originalmeeting'), 'id' => $id)) if (lavnn('withissues') <> '');
    srun($module, 'CopyMeetingParticipants', array('copyfrom' => lavnn('originalmeeting'), 'id' => $id)) if (lavnn('withparticipants') <> '');
  } else {
    $copyfrom = lavnn('copyfrom', $_REQUEST, '');
    srun($module, 'CopyMeetingIssues', array('copyfrom' => $copyfrom, 'id' => $id));
    $copyfromfolder = lavnn('copyfromfolder', $_REQUEST, '');
    srun($module, 'CopyFolderIssues', array('copyfrom' => $copyfromfolder, 'id' => $id));
  }
  go("?p=$module/viewmeeting&id=$id&tab=participants");
} else {
  $runtime->set_cookie('error', 'Could not create meeting!');
  go("?p=$module/newmeeting");
}

?>
