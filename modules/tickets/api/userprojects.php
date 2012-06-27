<?php
# Define help variable
$HELP = "
  user_id           int
  search            varchar
  access            varchar { 'read' | 'edit' | 'both' }
  
  SUCCESS
  <UserProjects>
    <Project>
      <id />
      <title />
      <moderator />
      <ModeratorName />
      <creator />
      <CreatorName />
      <DateUpdated />
      <tickets />
      <minutes />
      <Progress />
    </Project>
  </UserProjects>
  
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

# API logic - check user id and call db query
$user_id = $apiparams['user_id'] || 0;
$access = $apiparams['access'] || 'both';
$allowed = qw('read' 'edit' 'both');
$apiparams['access'] = 'both' if (!in_array($access, $allowed);
if ($user_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'UserProjects.InvalidInputParams', 'text' => 'User ID not specified.') );
} else {
  $searchInfo = array();
  $projects = $runtime->s2a($controller, 'SearchProjects', $apiparams);
  $searchInfo['projects'] = $projects;
  $output = $runtime->$r->txt->do_template($controller, 'API.UserProjects', $searchInfo);
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
