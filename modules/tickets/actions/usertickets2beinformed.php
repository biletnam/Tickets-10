<?php

$_REQUEST['user_id'] ||= $r['userID'];
$user_id = $_REQUEST['user_id'];
$pageParams = array('user_id' => $user_id);
$userInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $user_id)); 

if (count($userInfo) == 0) {
  $page->add('main', $r->txt->do_template($module, 'usertickets.nouser');
} elseif ($user_id == $r['userID'] || $acc->is_superadmin() || $userInfo['deputy_staff'] == $r['userID'] || $userInfo['line_manager'] == $r['userID']) {
  $notifying = $runtime->s2a($module, 'ListUserNotifyingTickets', array('id' => $r['userInfo']['staff_id']));
  if ($user_id == $r['userID']) {
    $pageParams['notifyingtickets'] = $notifying;
    $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.mytickets2beinformed', $pageParams);  
    $page->add('main', $r->txt->do_template($module, 'mytickets2beinformed', $pageParams);  
  } else {
    $pageParams['notifyingtickets'] = $notifying;
    $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.usertickets2beinformed', $userInfo);  
    $page->add('main', $r->txt->do_template($module, 'usertickets2beinformed', $pageParams);  
  }
} else {
  $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.usertickets2beinformed', $userInfo);  
  $page->add('main', $r->txt->do_template($module, 'usertickets.denied', $pageParams);
}




?>
