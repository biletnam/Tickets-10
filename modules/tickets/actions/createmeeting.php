<?php
$_REQUEST['creator'] = $r['userID'];
$id = $runtime->sid($module, 'CreateMeeting', $_REQUEST);
if ($id > 0) {
  $originalmeeting = lavnn('originalmeeting');
  if ($originalmeeting <> '') { # clone
    $runtime->db->sqlrun($module, 'CopyMeetingIssues', array('copyfrom' => lavnn('originalmeeting'), 'id' => $id)) if (lavnn('withissues') <> '');
    $runtime->db->sqlrun($module, 'CopyMeetingParticipants', array('copyfrom' => lavnn('originalmeeting'), 'id' => $id)) if (lavnn('withparticipants') <> '');
  } else {
    $copyfrom = lavnn('copyfrom', $_REQUEST, '');
    $runtime->db->sqlrun($module, 'CopyMeetingIssues', array('copyfrom' => $copyfrom, 'id' => $id));
    $copyfromfolder = lavnn('copyfromfolder', $_REQUEST, '');
    $runtime->db->sqlrun($module, 'CopyFolderIssues', array('copyfrom' => $copyfromfolder, 'id' => $id));
  }
  go("?p=$module/viewmeeting&id=$id&tab=participants");
} else {
  $runtime->$_SESSION['error'] = 'Could not create meeting!');
  go("?p=$module/newmeeting");
}

?>
