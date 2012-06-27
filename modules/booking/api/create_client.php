<?php

# Define help variable
$HELP = "
  MANDATORY
  fname1            varchar
  lname1            varchar
  email             varchar
    
  OPTIONAL WITH WARNING
  dob1              varchar  # DD.MM.YYYY 
  passport1         varchar
  fname2            varchar
  lname2            varchar
  passport2         varchar
  dob2              varchar  # DD.MM.YYYY 
  mobilephone       varchar 
  homephone         varchar 
  officephone       varchar
  merlin_id         int

  SUCCESSFUL
  <Client>
    <ID />
  </Client>  
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
if ($apiparams['fname1'] == '' || $apiparams['lname1'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'CreateClient.InvalidInputParams', 'text' => 'Either fname1 or lname1 is not provided') );
} elseif ($apiparams['email'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'RequestBooking.InvalidInputParams', 'text' => 'Email is not provided') );
} else {
  use objBooking;
  $objB = new objBooking($r);
  $client_id = $objB->create_client(%apiparams);
  if ($client_id > 0) {
    $output = $runtime->$r->txt->do_template($controller, 'API.CreateClient', array('client_id' => $client_id));
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'RequestBooking.Failure', 'text' => 'Could not create client') );
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
