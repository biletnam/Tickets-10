<?php
  
use objNotification;
use Data::Dumper;

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {

  $articleInfo = $runtime->s2r($module, 'GetArticleData', $_REQUEST);

  # Do the notification staff - if article is not draft nor is deleted!
  if ($articleInfo['draft'] <> 1 && $articleInfo['deleted'] <> 1) {
    # Clean up notifications that might be on a dashboard
    $sqlParams = ('user' => $r['userID'], 'id' => $id);
    $runtime->srun($module, 'DeleteArticleNotifications', $sqlParams);

    # Add new notification about updated article
    $objN = new objNotification($r);
    $articleInfo['EditorData'] = hash2ref(s2r('users', 'GetEmployeeInfo', array('id' => $r['userID'])));
    $articleInfo['notificationtext'] = lavnn('notificationtext', $_REQUEST, '');
    $notification_id = $objN->add_notification_utf8('editarticle', $id, dot('notification.subject', $articleInfo), dot('notification.digest', $articleInfo));
    if ($notification_id > 0) {
      $receivers = array();
      $a = $runtime->s2a('main', 'ListUsersForResource', array('source_type' => 'readarticle', 'source_id' => $id));
      foreach $userhash (@a) {
        push @receivers, hash2ref(('user_id' => $userhash['lngId'])) if $userhash['lngId'] <> $r['userID'];
      }
      $objN->distribute_notification($notification_id, @receivers);
      set_cookie('flash', 'Notification sent to '.count($receivers).' readers');
    } else {
      set_cookie('flash', 'Could not create notification. Notification text may be too long');
    }
    $runtime->save_timegauge(0, 1);
    #$runtime->save_timegauge(0, array($notification_id == 0));
  }
}
go("?p=$module/edit&id=$id&tab=notifications");

?>
