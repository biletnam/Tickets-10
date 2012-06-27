<?php

# Define help variable
$HELP = "
  MANDATORY
  entity_type   # currently only { 'client' }
  entity_id
  merlin_id

  SUCCESSFUL
  <Entity>
    <EntityType />
    <EntityID />
    <MerlinID />
  </Entity>  
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
if ($apiparams['entity_type'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'SaveMerlinID.InvalidInputParams', 'text' => 'Entity Type is needed') );
} elseif ($apiparams['entity_id'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'SaveMerlinID.InvalidInputParams', 'text' => 'Entity ID is not provided') );
} elseif ($apiparams['merlin_id'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'SaveMerlinID.InvalidInputParams', 'text' => 'Merlin ID is not provided') );
} else {
  use objBooking;
  $objB = new objBooking($r);
  $saveOperationDetails = $objB->save_merlin_id(%apiparams);
  if (count($saveOperationDetails) > 0) {
    $output = $runtime->$r->txt->do_template($controller, 'API.SaveMerlinID', $apiparams);  
  } else {
    push @errors, $runtime->hash2ref( ('code' => 'SaveMerlinID.Failure', 'text' => 'Could not save the merlin ID') );
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
