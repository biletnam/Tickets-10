<?php

# Define help variable
$HELP = "
  MANDATORY
  contract_id     int
  date_of_birth   varchar(10) MM.DD.YYYY

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

# API logic - check contract and date of birth
$contract = $apiparams['contract_id'] || '';
$dob = $apiparams['date_of_birth'] || '';
if ($contract <> '' && $dob <> '') {
  $memberData = $runtime->s2r($controller, 'CheckMemberPersonalDetails', $apiparams);
  if (count($memberData) > 0) {
    $output = $runtime->$r->txt->do_template($controller, 'API.Login', $memberData);
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'RequestWebLogin.InvalidPassword', 'text' => 'Combination of username and password does not match to any member') );
  }
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'RequestWebLogin.InvalidInputParams', 'text' => 'Either contract or date of birth or both are not provided') );
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
