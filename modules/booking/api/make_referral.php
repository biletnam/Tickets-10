<?php

# Define help variable
$HELP = "
  MANDATORY
  client_id       int
  firstname       varchar
  lastname        varchar
  email           varchar
  
  SUCCESSFUL 
  <NewReferral>
    <NewClientID />
    <NewReferralID />
    <MD5Hash />
  </NewReferral>
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

# API logic - save message to the database
$client_id = $apiparams['client_id'] || 0;
if ($client_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'MakeReferral.MissingClientID', 'text' => 'Client (Referent) id is not provided') );
} elseif ($apiparams['firstname'] == '' || $apiparams['lastname'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'MakeReferral.MissingName', 'text' => 'Referral name is not fully provided') );
} elseif ($apiparams['email'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'MakeReferral.MissingEmail', 'text' => 'Referral email is not provided') );
} else {
  use objBooking;
  $objB = new objBooking($r);
  $apiparams['referent'] = $client_id;
  $sqlResult = $objB->make_referral(%apiparams);
  if (count($sqlResult) == 0) {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'MakeReferral.UnknownFailure', 'text' => 'Unknown failure while creating referral client') );
  }
  $output = $runtime->$runtime->txt->do_template($controller, 'API.MakeReferral', $sqlResult);
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
