<?php
# Define help variable
$HELP = "
  creator           int
  reviewer          int
  handler           int
  status            char(3)
  minpriority       int 
  exactpriority     int
  duedate           varchar(10)  e.g. '30.11.2010'            
  titletext         varchar 
  id                int
  
  SUCCESS:
  <SearchTickets>
    <Ticket>
      <id />
      <EncodedTitle />
      <creator />
      <CreatorName />
      <reviewer />
      <ReviewerName />
      <handler />
      <HandlerName />
      <DateCreated />
      <DateUpdated />
      <status />
      <priority />
      <duedate />
      <project />
      <EncodedProjectTitle />
      <EncodedContents />
    </Ticket>
  </SearchTickets>
";

# Check if built-in parameter values are overloaded
$contenttype = $apiparams['_contenttype_'] || 'text/xml';
$charset = $apiparams['_charset_'] || 'utf-8';
$format = $apiparams['_format_'] || '';

# Return HELP string if it is requested
if (exists $apiparams['_help_']) {
  print "content-type: text/html; charset=utf-8\n\n";
  print "<pre>$HELP";
  exit();  
}
 
# Initialize first-level variables that appear in API output 
$result = 'OK';
$output = '';
$warnings = array();
$errors = array();

# API logic
$cleanparams = Arrays::exclude_keys($apiparams, qw(_contenttype_ _charset_ _format_ api _help_ _user_id_));
if (count($cleanparams) > 0) {
  $searchInfo = array(); 
  $tickets = $runtime->s2a($controller, 'SearchTickets', $apiparams); 
  $searchInfo['tickets'] = $tickets;
  $output = $runtime->$r->txt->do_template($controller, 'API.SearchTickets', $searchInfo);
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'SearchTickets.InvalidInputParams', 'text' => 'Specify at least search parameter!') );
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
