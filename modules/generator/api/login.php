<?php

# Check if built-in parameter values are overloaded
$contenttype = $apiparams['_contenttype_'] || 'text/xml';
$charset = $apiparams['_charset_'] || 'utf-8';
$format = $apiparams['_format_'] || '';
 
# Initialize first-level variables that appear in API output 
$result = 'OK';
$output = '';
$warnings = array();
$errors = array();

# API logic - check username and password
$username = $apiparams['username'] || '';
$password = $apiparams['password'] || '';
if ($username <> '' && $password <> '') {
  $loginInfo = $runtime->s2r($controller, 'CheckGeneratorLogin', $apiparams);
  if (count($loginInfo) > 0) {
    $output = $runtime->$runtime->txt->do_template($controller, 'API.Login', $loginInfo);
    # register pageview
    $runtime->('main', 'RegisterPageview', array('entity_type' => 'gen_user_login', 'entity_id' => $loginInfo['user_id'], 'viewer_type' => 'G', 'viewer_id' => 0));    
  } else {
    # Register login failure
    $runtime->('main', 'RegisterPageview', array('entity_type' => 'gen_user_login_failed', 'entity_id' => $loginInfo['user_id'], 'viewer_type' => 'G', 'viewer_id' => 0));    
    $runtime->('main', 'RegisterLoginFailure', array('user_type' => 'G', 'username' => $username, 'password' => $password));
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'Login.InvalidPassword', 'text' => 'Combination of username and password does not match to any generator') );
  }
} else {
  # Register login failure
  $runtime->('main', 'RegisterLoginFailure', array('user_type' => 'G', 'username' => $username, 'password' => $password));
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'Login.InvalidInputParams', 'text' => 'Either username or password or both are not provided') );
}

# Return resulting XML API output - quite similar to all APIs
print "content-type: $contenttype; charset=$charset;\n\n";
print $runtime->$runtime->txt->do_template($controller, 'API.Envelope', array(
  'result' => $result,
  'output' => $output,
  'warnings' => Arrays::a2xml($warnings, 'Warnings', 'Warning'),
  'errors' => Arrays::a2xml($errors, 'Errors', 'Error'),
));

1;

?>
