<?php

# Define help variable
$HELP = "
  MANDATORY
  obligation_id     int
  qty               int
  
  ONE OF TWO IS MANDATORY
  booking_id        int
  bookreq_id        int
  
  SUCCESSFUL
  <ProductUsage>
    <ID />
  </ProductUsage>  
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

# API logic 
$obligation_id = $apiparams['obligation_id'] || '';
$booking_id = $apiparams['booking_id'] || '';
$bookreq_id = $apiparams['bookreq_id'] || '';
$qty = $apiparams['qty'] || '';
if ($obligation_id == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'UseProductObligation.MissingContractNumber', 'text' => 'Obligation ID is not provided') );
} elseif ($booking_id == '' || $bookreq_id == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'UseProductObligation.MissingCode', 'text' => 'Booking ID or Booking request ID is not provided') );
} elseif ($qty == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'UseProductObligation.MissingQty', 'text' => 'Quantity is not provided') );
} else {
  # Mark obligation as used in the database
  $id = $runtime->sid($controller, 'UseProductObligation', $apiparams);
  if ($id > 0) {
    $output = $runtime->dotmod($controller, 'API.UseProductObligation', array('id' => $id));
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'UseProductObligation.Failure', 'text' => 'Could not complete INSERT operation:' . $runtime->spreview($controller, 'UseProductObligation', $apiparams)) );
  }
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
