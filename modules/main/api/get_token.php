<?php

# Define help variable
$HELP = "
  MANDATORY
  user_type         varchar  { C | U | G }
  user_id           int
  password          varchar
  
  OPTIONAL
  force             int - should be 1 to regenerate token if valid token exists
  
  SUCCESSFULL
  <GetToken>
    <Token>U:16:12kg43gAZ2lAifFjgsTg09rk390dklfg-034mr=gk3m</Token>
  </GetToken>  
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

$usertype = $apiparams['user_type'] || '';
$userid = $apiparams['user_id'] || '';
$password = $apiparams['password'] || '';
$force = $apiparams['force'] || '';

if ($usertype == '' || $userid == '') {
  $result = 'ERR';
  push @errors, $runtime->hash2ref( ('code' => 's2a.UserNotIdentified', 'text' => 'User not identified properly, provide both type and ID') );
} elseif ($password == '') {
  $result = 'ERR';
  push @errors, $runtime->hash2ref( ('code' => 's2a.PasswordNotGiven', 'text' => 'Password is missing') );
} else {
  # Try to find specified user
  $userData = array();
  if ($usertype == 'U') {
    %userData = $runtime->s2r($controller, 'CheckEmployeeLogin', array('user_id' => $userid, 'password' => $password) );
  } elseif ($usertype == 'C') {
    %userData = $runtime->s2r($controller, 'CheckClientLogin', array('user_id' => $userid, 'password' => $password) );
  } elseif ($usertype == 'G') {
    %userData = $runtime->s2r($controller, 'CheckGeneratorLogin', array('user_id' => $userid, 'password' => $password) );
  } else {
    $result = 'ERR';
    push @errors, $runtime->hash2ref( ('code' => 's2a.InvalidUserType', 'text' => 'Invalid user type, use G, C or U') );
  } 
  # Build token or return error
  if (count($userData) > 0) {
    $token = '';
    # Try to find active token
    $tokenInfo = $runtime->s2r($controller, 'GetTokenInfo', $apiparams);
    if (count($tokenInfo) > 0) {
      $token = $tokenInfo['access_token'];      
    }
    # Create a token if it does not exist or if it is forced by parameter
    if ($token == '' || $force == '1') {
      use Digest::MD5 qw(md5_hex);
      $scrambled = $userid + 123456; # Additional funny obstacle
      $md5hash = md5_hex($usertype.$scrambled.rand(100));
      $token = $apiparams['access_token'] = "$usertype:$scrambled:$md5hash";
      $runtime->($controller, 'IssueAccessToken', $apiparams);
      $runtime->($controller, 'RenewAccessToken', $apiparams);
    }
    $output = "<GetToken><Token>$token</Token></GetToken>";
  } else {
    $result = 'ERR';
    push @errors, $runtime->hash2ref( ('code' => 's2a.InvalidUserType', 'text' => 'No user found') );
  }
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
