<?php

$userInfo = $runtime->s2r('users', 'GetUserLoginInfo', $_REQUEST);

if ((scalar keys %userInfo) == 0) {
  # Register login failure
  srun('main', 'RegisterPageview', array('entity_type' => 'employee_login_failed', 'entity_id' => $user_id, 'viewer_type' => 'U', 'viewer_id' => 0));    
  srun('main', 'RegisterLoginFailure', array('user_type' => 'U', 'username' => $_REQUEST['username'], 'password' => $_REQUEST['password']));
  set_cookie('error', 'Check password you are entering');
  # Flash message that user does not exist and return to login screen
  set_cookie('error', 'No such user');
  go('?p=users/login');
} elseif($userInfo['psswrd'] <> $_REQUEST['password']) {
  # Register login failure
  srun('main', 'RegisterPageview', array('entity_type' => 'employee_login_failed', 'entity_id' => $user_id, 'viewer_type' => 'U', 'viewer_id' => 0));    
  srun('main', 'RegisterLoginFailure', array('user_type' => 'U', 'username' => $_REQUEST['username'], 'password' => $_REQUEST['password']));
  set_cookie('error', 'Check password you are entering');
  # Go back to login screen #TODO implement entering wrong password limited amount of times
  go('?p=users/login');
} elseif($userInfo['fired'] == 1) {
  set_cookie('error', 'User '.$_REQUEST['username'].' cannot log in because '.($userInfo['lngSex'] == 1 ? '' : 's').'he is fired');
  # Go back to login screen #TODO implement entering wrong password limited amount of times
  go('?p=users/login');
} else {
  # everything's fine. Let's go to last requested URL or homepage
  $sessionInfo = $runtime->s2r('users', 'GetUserSession', $userInfo);
  if ((scalar keys %sessionInfo) > 0) {
    # User has logged before
    $sessionInfo['remote_ip'] = $r['IP'];
    $sessionInfo['sessionID'] = $sessionInfo['temp_id'];
    srun('users', 'UpdateUserSession', $sessionInfo); # TODO screen width and separators are not updated 
  } else {
    # User logs in for the first time
    $sessionInfo['id'] = $userInfo['id'];
    $sessionInfo['sessionID'] = genNewSessionID();
    $sessionInfo['remote_ip'] = $r['IP'];
    $sessionInfo['screen_width'] = 1024; # TODO real one 
    $sessionInfo['time_diff'] = 0; # TODO real one
    $sessionInfo['decimal_sep'] = '.'; # TODO real one
    $sessionInfo['thousands_sep'] = ''; # TODO real one
    srun('users', 'InsertUserSession', $sessionInfo); 
  }
  set_cookie('sessionID', $sessionInfo['sessionID']);
  # register pageview
  srun('main', 'RegisterPageview', array('entity_type' => 'employee_login', 'entity_id' => $userInfo['id'], 'viewer_type' => 'U', 'viewer_id' => 0));    
  # Go to last requested page or default page
  $nextURL = lavnn('nextURL') <> '' ? '?'.lavnn('nextURL') : '?p='.$_CONFIG['DEFAULT_ACTION']; 
  go($nextURL);
}

?>
