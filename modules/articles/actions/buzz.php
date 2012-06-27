<?php
use objNotification;

$id = lavnn('article', $_REQUEST, 0);
if ($id > 0) {
  # Get data of the article
  $articleInfo = $runtime->s2r($module, 'GetArticleData', array('id' => $id));
  # Add new notification about updated article on the dashboard
  $objN = new objNotification($r);
  $articleInfo['buzz'] = lavnn('message');
  $userInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $r['userID']));
  $articleInfo['sender'] = $r->txt->do_template($module, 'employee.fullname', $userInfo);
  $msgbody = $r->txt->do_template($module, 'buzz.message', $articleInfo);
  $notification_id = $objN->add_notification('articlereviewbuzz', $id, dot('buzz.subject', $articleInfo), $msgbody);
  if ($notification_id > 0) {
    $receivers = array();
    $users = $runtime->s2a('main', 'ListUsersForResource', array('source_type' => 'articlereviewers', 'source_id' => $id));
    foreach ($users as $userhash) {
      $receivers[] = array('user_id' => $userhash['lngId']); 
    }
    if (count($receivers) > 0) {
      $objN->distribute_notification($notification_id, $receivers);
      $_SESSION['flash'] = 'Buzz sent';
    }
  }
  go("?p=$module/review&id=$id");
} else {
  go("?p=$module/myarticles");
}

?>
