<?php

# Define help variable
$HELP = "
  MANDATORY
  client_id       int
  
  SUCCESSFUL 
  <MemberPaymentInfo>
    <Fees>
      <Fee>
        <Year />
        <Amount />
        <Currency />
        <DatePayment />
        <Paid />
        <Method />
        <Commentary />
      </Fee>
    </Fees>
  </MemberPaymentInfo>
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
$client_id = $apiparams['client_id'] || '';
if ($client_id <> '') {
  $fees = $runtime->s2a($controller, 'ListMaintenanceFees', $apiparams);
  if (count($fees) > 0) {
    $memberPaymentInfo = array('fees' => $fees);
    $output = $runtime->dotmod($controller, 'API.MemberPaymentInfo', $memberPaymentInfo);
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'GetMemberPaymentInfo.NotFound', 'text' => 'Member payment info was not found') );
  }
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetMemberPaymentInfo.InvalidInputParams', 'text' => 'Client ID was not provided') );
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
