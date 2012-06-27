<?php

# Define help variable
$HELP = "
  MANDATORY
  md5hash     varchar
  
  SUCCESSFULL
  <ReferralInfo>
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
    <DateInvited />
    <DateAccepted />
    <DateConfirmed />
  </ReferralInfo>  
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
$md5hash = $apiparams['md5hash'] || '';
if ($md5hash == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetReferral.MissingMD5Hash', 'text' => 'MD5 hash is not provided') );
} else {
  $referralInfo = $runtime->s2r($controller, 'GetReferral', $apiparams); 
  $output = $runtime->$r->txt->do_template($controller, 'API.GetReferral', $referralInfo);
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
