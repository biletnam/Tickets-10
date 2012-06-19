<?php

# Define help variable
$HELP = "
  MANDATORY
  client_id     int
  
  SUCCESSFUL
  <MemberReferrals>
    <Referral>
      <Referent>
        <ID />
        <FirstName />
        <LastName />
        <Email />
      </Referent>
      <Referral>
        <ID />
        <FirstName />
        <LastName />
        <Email />
      </Referral>
      <StatusCode />
      <StatusName />
      <DateInvited >
      <DateAccepted />
      <DateConfirmed />
      <MD5Hash />
    </Referral>
  </MemberReferrals>  
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

$expiration_days = $apiparams['expiration_days'] = array($apiparams['expiration_days'] || 28);
 
# Initialize first-level variables that appear in API output 
$result = 'OK';
$output = '';
$warnings = array();
$errors = array();

# API logic - save message to the database
$client_id = $apiparams['client_id'] || 0;
if ($client_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetMemberReferrals.MissingClientID', 'text' => 'Client id is not provided') );
} else {
  $referrals = $runtime->s2a($controller, 'ListMemberReferrals', $apiparams);
  $output = $runtime->$runtime->txt->do_template($controller, 'API.GetMemberReferrals', array('referrals' => $referrals));
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
