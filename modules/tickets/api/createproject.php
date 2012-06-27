<?php
# Define help variable
$HELP = "
  user_id           int
  moderator         int
  title             varchar
  
  SUCCESS
  <CreateProject>
    <Project>
      <id />
      <EncodedTitle />
      <creator />
      <CreatorName />
      <moderator />
      <ModeratorName />
      <DateCreated />
      <DateUpdated />
    </Project>
  </CreateProject>
";

# Check if built-in parameter values are overloaded
$contenttype = $apiparams['_contenttype_'] || 'text/xml';
$charset = $apiparams['_charset_'] || 'utf-8';
$format = $apiparams['_format_'] || '';
$user_id = $apiparams['user_id'];

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

$used_id = $apiparams['user_id'] || 0;
if ($used_id > 0) {
  $moderator = $apiparams['moderator'] || $user_id;
  $apiparams['moderator'] = $moderator;
  $title = $apiparams['title'] || '';
  if ($title <> '') {
    $newid = $objT->create_project(%apiparams);
    if ($newid > 0) {
      $projectInfo = $runtime->s2r($controller, 'GetProjectInfo', array('id' => $newid));
      $output = $runtime->$r->txt->do_template($controller, 'API.CreateProject', $projectInfo);
    } else {
      $result = 'ERR'; 
      push @errors, $runtime->hash2ref( ('code' => 'CreateProject.Failure', 'text' => 'Could not create a project with given parameters') );
    }
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'CreateProject.InvalidInputParams', 'text' => 'Title for project should be specified!') );
  }
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'CreateProject.InvalidInputParams', 'text' => 'User should be defined!') );
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
