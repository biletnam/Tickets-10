<?php

# Define help variable
$HELP = "
  MANDATORY
  client_id         int
  message_id        int

  SUCCESFULL
  <MessageInfo>
    <ID />
    <Content />
    <DateCreated /
    <DateRead />
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
$message_id = $apiparams['message_id'] || 0;
if ($client_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'UpdateMemberMessage.MissingClientID', 'text' => 'Client ID is not provided') );
} elseif ($message_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'UpdateMemberMessage.MissingMessageID', 'text' => 'Message ID is not provided') );
} else {
  $messages = $runtime->s2a($controller, 'ListMemberMessages', $apiparams);
  if (count($messages) == 1) {
    $runtime->($controller, 'UpdateMemberMessage', $apiparams);
    # Fetch this message anew to return it
    @messages = $runtime->s2a($controller, 'ListMemberMessages', $apiparams);
    $output = $runtime->$runtime->txt->do_template($controller, 'API.UpdateMemberMessage', ${$messages[0]});
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'UpdateMemberMessage.InvalidInputParams', 'text' => 'Message with provided ID cannot be found') );
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
