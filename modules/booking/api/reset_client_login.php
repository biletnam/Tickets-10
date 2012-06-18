<?php

# Define help variable
$HELP = "
  MANDATORY
  client_id
  OR
  contract_no

  SUCCESSFUL
  <ClientData>
    <ClientID />
    <Username />
  </ClientData>  
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
if ($apiparams['client_id'] <> '' || $apiparams['contract_no'] <> '') {
  $clientData = $runtime->s2r($controller, 'GetClientData', $apiparams); 
  if (count($clientData) > 0) {
    $result = $runtime->srun($controller, 'ResetClientLogin', array('client_id' => $clientData['client_id']));
    if ($result > 0) {
      $output = $runtime->dotmod($controller, 'API.ResetClientLogin', $clientData);  
    } else {
      $result = 'ERR'; 
      push @errors, $runtime->hash2ref( ('code' => 'ResetClientLogin.Failure', 'text' => 'Could not reset the login date') );
    }
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'ResetClientLogin.NoClientFound', 'text' => 'Client was not found') );
  }  
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'ResetClientLogin.InvalidInputParams', 'text' => 'Invalid Input Parameters. Either Client ID or Contract No is missing') );
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
