<?php

# Define help variable
$HELP = "
  MANDATORY
  username       varchar
  password       varchar
  
  SUCCESSFUL 
  <MemberInfo>
    <ID />
    <FirstName />
    <LastName />
    <IsFirstLogin />
    <DateLastLogin />
    <DaysNotSeen />
  </MemberInfo>
";

# Return HELP string if it is requested
if (exists $apiparams['_help_']) {
  use HTML::Entities;
  print "content-type: text/html; charset=utf-8\n\n";
  print "<pre>".encode_entities($HELP);
  exit();  
}

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
$hardcoded = '';
if ($username == 'vacation' && $password == 'absolute') {
  $hardcoded = 'Vacation';
}
if ($hardcoded == 'Vacation') {
  $output = $runtime->dotmod($controller, 'API.Login.Hardcoded.Vacation', $apiparams);
  push @warnings, $runtime->hash2ref( ('code' => 'MemberLogin.Hardcoded', 'text' => 'Combination of username and password matched to one hardcoded for Vacation') );
} elseif ($username <> '' && $password <> '') {
  $memberData = $runtime->s2r($controller, 'CheckMemberLogin', $apiparams);
  if (count($memberData) > 0) {
    $contracts = $runtime->s2a($controller, 'ListMemberContracts', $memberData);
    $memberData['contracts'] = $contracts;
    $output = $runtime->dotmod($controller, 'API.Login', $memberData);
    $runtime->srun($controller, 'LogMemberLogin', $memberData);
  } else {
    $result = 'ERR'; 
    $runtime->srun('main', 'RegisterPageview', array('entity_type' => 'client_login_failed', 'entity_id' => $memberData['client_id'], 'viewer_type' => 'C', 'viewer_id' => 0));    
    $runtime->srun('main', 'RegisterLoginFailure', array('user_type' => 'C', 'username' => $username, 'password' => $password));
    push @errors, $runtime->hash2ref( ('code' => 'MemberLogin.InvalidPassword', 'text' => 'Combination of username and password does not match to any member') );
  }
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'MemberLogin.InvalidInputParams', 'text' => 'Either username or password or both are not provided') );
}

# Return resulting XML API output - quite similar to all APIs
print "content-type: $contenttype; charset=$charset;\n\n";
print $runtime->dotmod($controller, 'API.Envelope', array(
  'result' => $result,
  'output' => $output,
  'warnings' => Arrays::a2xml($warnings, 'Warnings', 'Warning'),
  'errors' => Arrays::a2xml($errors, 'Errors', 'Error'),
));

1;

?>
