<?php

$id = lavnn('id');
$usertype = lavnn('user_type');
$userid = lavnn('user_id');
$takenPollDetails = $runtime->s2r($module, 'GetTakenPollDetails', $_REQUEST);
if (count($takenPollDetails) > 0) {
  $runtime->db->sqlrun($module, 'AnnulatePollResults', $_REQUEST);
  $taken = $takenPollDetails['id'];
  $runtime->db->sqlrun($module, 'DeleteTakenPollDetail', array('id' => $taken)); 
  $_SESSION['flash'] = 'Poll results annulated for user $userid');
}
if ($id <> '') {
  go("?p=$module/pollstats&id=$id&tab=persons");
} else {
  go("?p=$module/list");
}

?>
