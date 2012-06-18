<?php
# Define help variable
$HELP = "
  editor            int
  destination       varchar { 'self' | 'employee' | 'project' }
  employee          int
  project           int
  title             varchar
  contents          varchar
  priority          int    { -1=Low | 0=Normal | 1=High | 2=Critical | 3=Panic! }
  duedate           varchar(10)  e.g. '30.11.2010'            
  target            varchar e.g. 'employee:16,project:21'
  notified          varchar e.g. '16,97433,92587' 
";

# Check if built-in parameter values are overloaded
$contenttype = $apiparams['_contenttype_'] || 'text/xml';
$charset = $apiparams['_charset_'] || 'utf-8';
$format = $apiparams['_format_'] || '';
$user_id = $apiparams['_user_id_'];
$runtime->set_userID($user_id) if $user_id <> '';

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
use objTicketing;
$objT = new objTicketing($r);

$editor = $apiparams['editor'] || 0;
if ($editor > 0) {
  $destination = $apiparams['destination'] || '';
  $allowed = qw('self' 'project' 'employee');
  if (in_array($destination, $allowed) {
    $title = $apiparams['title'] || '';
    if ($title <> '') {
      $newid = $objT->create_ticket(%apiparams);
      if ($newid > 0) {
        $notifiedPersons = $apiparams['notified'] || '';
        if ($notifiedPersons <> '') {
          $nps = split(',', $notifiedPersons);
          foreach $np (@nps) {
            $objT->add_notification_recipient($newid, array('editor' => $editor, 'staff_id' => trim($np)));
          }
        }
        # Return new ticket information
        $ticketInfo = $runtime->s2r($controller, 'GetTicketInfo', array('id' => $newid));
        $output = $runtime->dotmod($controller, 'API.CreateTicket', $ticketInfo);
      } else {
        $result = 'ERR'; 
        push @errors, $runtime->hash2ref( ('code' => 'CreateTicket.Failure', 'text' => 'Could not create a ticket with given parameters') );
      }
    } else {
      $result = 'ERR'; 
      push @errors, $runtime->hash2ref( ('code' => 'CreateTicket.InvalidInputParams', 'text' => 'Title for ticket should be specified!') );
    }
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'CreateTicket.InvalidInputParams', 'text' => 'Destination for the ticket should be specified!') );
  }
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'CreateTicket.InvalidInputParams', 'text' => 'Editor should be defined!') );
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
