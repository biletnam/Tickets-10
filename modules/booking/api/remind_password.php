<?php

# Define help variable
$HELP = "
  ONE OF TWO IS MANDATORY
  email        varchar
  contract_id  varchar
  
  SUCCESS RESULTS
  <RemindPassword>
    <UserCredentials>
      <ID />
      <Email />
      <Username />
      <Password />
      <FirstName />
      <LastName />
    </UserCredentials>
  </RemindPassword>
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

# API logic - check contract and other inputs
if ($apiparams['email'] == '' && $apiparams['contract_id'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'RemindPassword.InvalidInputParams', 'text' => 'Either email or contract should be provided') );
} else {
  $loginInformation = $runtime->s2a($controller, 'MatchLoginCredentials', $apiparams);
  if (count($loginInformation) > 0) {
    $output = $runtime->$runtime->txt->do_template($controller, 'API.RemindPassword', array('credentials' => $loginInformation));
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'RemindPassword.NothingFound', 'text' => 'Failed to find login information using data provided') );
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
