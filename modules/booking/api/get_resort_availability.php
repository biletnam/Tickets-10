<?php

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
$resort_id = $apiparams['resort_id'] || '';
$start_date = $apiparams['start_date'] || '';
$end_date = $apiparams['end_date'] || '';
$apt_types = $apiparams['apt_types'] || ''; # optional

if ($resort_id <> '' && $start_date <> '' && $end_date <> '') {
  $availabilityInfo = array();
#  $slots = $runtime->s2a($controller, 'GetAvailabilitySlots', $apiparams); 
  $slots = $runtime->s2a($controller, 'ListAvailableHardBlocks', $apiparams);
  $availabilityInfo['slots'] = $slots;
  $output = $runtime->$r->txt->do_template($controller, 'API.AvailabilityInfo', $availabilityInfo);
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetResortAvailability.InvalidInputParams', 'text' => 'Either resort or dates are not provided') );
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
