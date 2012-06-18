<?php

$id = lavnn('id');
$usertype = lavnn('user_type');
$userid = lavnn('user_id');
$takenPollDetails = $runtime->s2r($module, 'GetTakenPollDetails', $_REQUEST);
if (count($takenPollDetails) > 0) {
  srun($module, 'AnnulatePollResults', $_REQUEST);
  $taken = $takenPollDetails['id'];
  srun($module, 'DeleteTakenPollDetail', array('id' => $taken)); 
  set_cookie('flash', 'Poll results annulated for user $userid');
}
if ($id <> '') {
  go("?p=$module/pollstats&id=$id&tab=persons");
} else {
  go("?p=$module/list");
}

?>
