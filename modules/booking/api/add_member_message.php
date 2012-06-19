<?php

$HELP = "
  MANDATORY
  client_id     int
  message_type  varchar
  content       varchar
  
  SUCCESSFUL
  <MessageInfo>
    <ID />
  </MessageInfo>  
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
$messagetype = $apiparams['message_type'] || '';
$content = $apiparams['content'] || '';
if ($client_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'AddMemberMessage.MissingClientID', 'text' => 'Client id is not provided') );
} elseif ($messagetype <> '' && $content <> '') {
  $id = $runtime->sid($controller, 'AddMemberMessage', $apiparams);
  if ($id > 0) {
    $output = $runtime->$runtime->txt->do_template($controller, 'API.AddMemberMessage', array('id' => $id));
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'AddMemberMessage.Failure', 'text' => 'Could not complete INSERT operation:' . $runtime->spreview($controller, 'AddMemberMessage', $apiparams)) );
  }
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'AddMemberMessage.InvalidInputParams', 'text' => 'Either message type or content or both are not provided') );
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
