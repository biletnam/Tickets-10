<?php

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
$table_name = $apiparams['table_name'] || '';
$merlin_id = $apiparams['merlin_id'] || '';
$abs_id = $apiparams['abs_id'] || '';
if ($table_name == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'a2m.InvalidInputParams', 'text' => 'Table name not provided') );
} elseif ($merlin_id == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'a2m.InvalidInputParams', 'text' => 'Merlin ID not provided') );
} elseif ($abs_id == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'a2m.InvalidInputParams', 'text' => 'Absolute ID not provided') );
} else {
  $result = $runtime->sid($controller, 'a2m', $apiparams);
  if ($result == 0) {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'a2m.SyncNotRegistered', 'text' => 'Registration of Absolute2Merlin syncronization failed') );
  } else {
    $syncInfo = $runtime->s2r($controller, array('id' => $result));
    $output = $runtime->$runtime->txt->do_template($controller, 'a2m', $syncInfo);
  }    
}

# Return resulting XML API output - quite similar to all APIs
print "content-type: $contenttype; charset=$charset;\n\n";
print $runtime->$runtime->txt->do_template($controller, 'Envelope', array(
  'result' => $result,
  'output' => $output,
  'warnings' => Arrays::a2xml($warnings, 'Warnings', 'Warning'),
  'errors' => Arrays::a2xml($errors, 'Errors', 'Error'),
));

1;

?>
