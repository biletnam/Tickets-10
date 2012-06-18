<?php
use objNotification;

$id = lavnn('id', $_REQUEST, 0);
$article = lavnn('article', $_REQUEST, 0);
$_REQUEST['resource_type'] = 'article';
$_REQUEST['resource_id'] = $article;
$_REQUEST['user_id'] = $r['userID'];

if ($id == 0 && $article <> 0) {
  $newstatus = $runtime->s2r('main', 'InsertReviewStatus', $_REQUEST);
  $id = $newstatus['id']; # we need this instead of sid() because stored procedure adds to history table after adding a review status
} elseif($id <> 0 && $article == 0) {
  $statusinfo = $runtime->s2r('main', 'GetReviewStatus', array('id' => $id));
  $article = $statusinfo['resource_id'];
  srun('main', 'UpdateReviewStatus', $_REQUEST);
}


if ($article > 0) {
  $articleInfo = $runtime->s2r($module, 'GetArticleData', array('id' => $article));
  $reviewerInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $r['userID']));
  $articleInfo['sender'] = $runtime->txt->do_template($module, 'employee.fullname', $reviewerInfo);
  $articleInfo['message'] = lavnn('comment') || ' no comment ';
  if (lavnn('status') == '') {
    $articleInfo['explanation'] = ' withdrew their opinion, you may buzz them again!';
  } elseif (lavnn('status') == '0') {
    $articleInfo['explanation'] = ' declined this article with following comment:';
  } elseif (lavnn('status') == '1') {
    $articleInfo['explanation'] = ' approved this article with following comment:';
  }
  $msgsubj = $runtime->txt->do_template($module, 'articlereview.subject', $articleInfo);
  $msgbody = $runtime->txt->do_template($module, 'articlereview.body', $articleInfo);
  $objN = new objNotification($r);
  $notification_id = $objN->add_notification('articlereview', $article, $msgsubj, $msgbody);
  if ($notification_id > 0) {
    $receivers = array(hash2ref(('user_id' => $articleInfo['Author'])));
    $objN->distribute_notification($notification_id, @receivers);
  }
  set_cookie('flash', 'Your opinion is saved');
  go("?p=$module/review&id=$article");
} else {
  go("?p=$module/home");
}
?>
