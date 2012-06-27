<?php

$_REQUEST['user_id'] ||= $r['userID'];
$user_id = $_REQUEST['user_id'];
$pageParams = array('user_id' => $user_id);
$userInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $user_id)); 

if (count($userInfo) == 0) { 
  $page->add('main', $r->txt->do_template($module, 'usertickets.nouser');
} elseif ($user_id == $r['userID'] || $acc->is_superadmin() || $acc->is_line_manager($user_id) || $acc->is_deputy_staff($user_id)) { 
  $stats = $runtime->s2a($module, 'ListUserTicketStats', array('user_id' => $user_id));
  if ($pageParams['user_id'] == $r['userID']) { 
    $pageParams['stats'] = $stats;
    $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.mytickets', $pageParams);  
    $page->add('main', $r->txt->do_template($module, 'mytickets', $pageParams);  
  } else { 
    $userInfo['stats'] = $stats;
    $page->add('title',  $userInfo['pagetitle'] = $r->txt->do_template($module, 'title.usertickets', $userInfo);
    $page->add('main', $r->txt->do_template($module, 'usertickets', $userInfo);  
  }
} else { 
  $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.usertickets2handle', $userInfo);  
  $page->add('main', $r->txt->do_template($module, 'usertickets.denied', $pageParams);
}




?>
