<?php

# Define help variable
$HELP = "
  MANDATORY
  username      varchar
  password      varchar
  newpassword   varchar

  SUCCESSFUL
  <PasswordChanged>
    <Username />
    <NewPassword />
  </PasswordChanged>
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
$newpassword = $apiparams['newpassword'] || '';
if ($password == $newpassword) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'ChangePassword.ShouldDiffer', 'text' => 'New password should differ from the old one') );
} elseif ($newpassword == '' || $newpassword == $username || length($newpassword) < 6) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'ChangePassword.InvalidPassword', 'text' => 'New password should differ from user name and be at least 6 characters long') );
} elseif ($username <> '' && $password <> '') {
  $memberData = $runtime->s2r($controller, 'CheckMemberLogin', $apiparams);
  if (count($memberData) == 0) {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'ChangePassword.InvalidOldPassword', 'text' => 'Wrong old password was provided') );
  } else {
    $runtime->($controller, 'ChangeMemberPassword', array('client_id' => $memberData['client_id'], 'newpassword' => $newpassword));
    # try the new password to confirm the successful change
    $memberData = $runtime->s2r($controller, 'CheckMemberLogin', array('username' => $username, 'password' => $newpassword));
    if (count($memberData) == 0) {
      $result = 'ERR'; 
      push @errors, $runtime->hash2ref( ('code' => 'ChangePassword.UpdateFailure', 'text' => 'Could not update the database') );
    } else {
      $output = $runtime->$r->txt->do_template($controller, 'API.ChangePassword', $apiparams);
    }  
  }
}

# Return resulting XML API output - quite similar to all APIs
print "content-type: $contenttype; charset=$charset;\n\n";
print $runtime->$r->txt->do_template($controller, 'API.Envelope', array(
  'result' => $result,
  'output' => $output,
  'warnings' => Arrays::a2xml($warnings, 'Warnings', 'Warning'),
  'errors' => Arrays::a2xml($errors, 'Errors', 'Error'),
));

1;

?>
