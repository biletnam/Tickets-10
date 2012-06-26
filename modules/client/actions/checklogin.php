<?php

$result = $runtime->s2r($module, 'CheckClientLogin', $_REQUEST);
if (count($result) > 0) {
  $sessionInfo = $runtime->s2r('users', 'GetWebSession', array('type' => 'client', 'id' => $result['client_id']));
  if ((scalar keys %sessionInfo) > 0) {
    # User has logged before
    $sessionInfo['remote_ip'] = $r['IP'];
    $runtime->db->sqlrun('users', 'UpdateWebSession', $sessionInfo); 
  } else {
    # User logs in for the first time
    $sessionInfo['type'] = 'client';
    $sessionInfo['id'] = $result['client_id'];
    $sessionInfo['GUID'] = genNewSessionID();
    $sessionInfo['ip'] = $r['IP'];
    $runtime->db->sqlrun('users', 'InsertWebSession', $sessionInfo); 
  }
  set_cookie('sessionID_client', $sessionInfo['GUID']);
  # register pageview
  $runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'client_login', 'entity_id' => $result['client_id'], 'viewer_type' => 'C', 'viewer_id' => 0));    
  # go to last requested page or default page
  $nextURL = lavnn('nextURL') <> '' ? '?'.lavnn('nextURL') : '?p='.$_CONFIG['DEFAULT_CLIENT_URL']; 
  go2('client.pl', $nextURL);
} else {
  # Register login failure
  $runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'client_login_failed', 'entity_id' => $result['client_id'], 'viewer_type' => 'C', 'viewer_id' => 0));    
  $runtime->db->sqlrun('main', 'RegisterLoginFailure', array('user_type' => 'C', 'username' => $result['client_id'], 'password' => $result['password']));
  # Go back to login screen
  $_SESSION['error'] = 'Invalid client ID or password ');
  go2('client.pl', "?p=$module/login");  
}

1;

?>
