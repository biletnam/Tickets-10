<?php

# Define help variable
$HELP = "
  MANDATORY
  token         varchar (although, if ommitted, session cookie is searched)
  
  SUCCESSFULL
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

# Check security
$accessToken = $apiparams['token'] || '';
if ($accessToken == '') {
  $result = 'ERR';
  push @errors, $runtime->hash2ref( ('code' => 's2a.NoAccess', 'text' => 'Access token is missing') );
} else {
  ($userType, $userID, $accessToken) = split(':', $accessToken);
  $accessTokenInfo = $runtime->s2r($controller, 'GetTokenInfo', array('user_type' => $userType, 'user_id' => $userID - 123456, 'token' => $accessToken));
  if (count($accessTokenInfo) == 0) {
    $result = 'ERR';
    push @errors, $runtime->hash2ref( ('code' => 's2a.InvalidToken', 'text' => 'Invalid token provided, get a new one using main/get_token API') );
  } else {
    # Run the query
    $dbh = $r['db']['db'];
    if (!$dbh) {
      $result = 'ERR';
      push @errors, $runtime->hash2ref( ('code' => 'srun.NoDatabase', 'text' => 'Database is not ready') );
    } else {
      $query = $apiparams['q'];
      if ($query == '') {
        $result = 'ERR';
        push @errors, $runtime->hash2ref( ('code' => 'srun.EmptyQuery', 'text' => 'Empty query was sent') );
      } else {
        $sth = $dbh->prepare($query);
        if (!$sth) {
          $result = 'ERR';
          push @errors, $runtime->hash2ref( ('code' => 'srun.InvalidQuery', 'text' => "Could not prepare query: $query") );
        } else {
          $dbh->do($query);
        }
      }
    }
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
