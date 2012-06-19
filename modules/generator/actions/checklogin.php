<?php
$username = lavnn('username');
$password = lavnn('password');
$urlsuccess = lavnn('nextUrl') || "?p=$module/balance"; 
#formdebug($_REQUEST); die($urlsuccess);
$urlfailure = lavnn('urlfailure') || "?p=$module/promptlogin";
$user_id = 0;
if ($username <> '' && $password <> '') {
  $loginInfo = $runtime->s2r($module, 'CheckGeneratorLogin', $_REQUEST);
  if (count($loginInfo) > 0) {
    $user_id = $loginInfo['user_id'];
    $runtime->set_cookie('gen_user_id', $user_id);
    # register pageview
    $runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'gen_user_login', 'entity_id' => $user_id, 'viewer_type' => 'G', 'viewer_id' => 0));    
  } else {
    # Register login failure
    $runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'gen_user_login_failed', 'entity_id' => $user_id, 'viewer_type' => 'G', 'viewer_id' => 0));    
    $runtime->db->sqlrun('main', 'RegisterLoginFailure', array('user_type' => 'G', 'username' => $username, 'password' => $password));
  }
} 
go($user_id > 0 ? $urlsuccess : $urlfailure);

?>
