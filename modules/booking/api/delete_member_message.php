<?php

# Define help variable
$HELP = "
  MANDATORY
  client_id       int
  message_id      int
  
  SUCCESSFUL 
  <MessageInfo>
    <ID />
    <Content />
    <DateCreated />
    <DateRead />
    <DateDeleted />
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

# API logic
$client_id = $apiparams['client_id'] || 0;
$message_id = $apiparams['message_id'] || 0;
if ($client_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'DeleteMemberMessage.MissingClientID', 'text' => 'Client ID is not provided') );
} elseif ($message_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'DeleteMemberMessage.MissingMessageID', 'text' => 'Message ID is not provided') );
} else {
  $messages = $runtime->s2a($controller, 'ListMemberMessages', $apiparams);
  if (count($messages) == 1) {
    $runtime->($controller, 'DeleteMemberMessage', $apiparams);
    # Fetch this message anew to see if it was deleted
    @messages = $runtime->s2a($controller, 'ListMemberMessages', $apiparams);
    if (count($messages) == 1) {
      $message = $messages[0]; 
      if ($message['DateDeleted'] == '') {
        $result = 'ERR'; 
        push @errors, $runtime->hash2ref( ('code' => 'DeleteMemberMessage.Failure', 'text' => 'Could not complete DELETE operation:' . $runtime->spreview($controller, 'DeleteMemberMessage', $apiparams)) );
      } else {
        $output = $runtime->$runtime->txt->do_template($controller, 'API.DeleteMemberMessage', $message);
      }
    }
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'DeleteMemberMessage.InvalidInputParams', 'text' => 'Message with provided ID cannot be found') );
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
