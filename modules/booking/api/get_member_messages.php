<?php

# Define help variable
$HELP = "
  MANDATORY
  client_id       int
  
  OPTIONAL
  message_type    int
  message_id      int
  
  SUCCESSFUL 
  <MemberMessages>
    <Message>
      <ID />
      <MessageType />
      <Content />
      <DateCreated />
      <DateRead> />
    </Message>
  </MemberMessages>
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
$messageid = $apiparams['message_id'] || '';
if ($client_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetMemberMessages.MissingClientID', 'text' => 'Client id is not provided') );
} else {
  $messages = $runtime->s2a($controller, 'ListMemberMessages', $apiparams);
  $output = $runtime->$r->txt->do_template($controller, 'API.GetMemberMessages', array('messages' => $messages));
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
