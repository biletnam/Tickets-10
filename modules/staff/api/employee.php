<?php

# Define help variable
$HELP = "

  Returns info about employee

  MANDATORY
  id       int
  
  SUCCESSFUL 
  <Employee>
    <FirstName />
    <LastName />
    <Nick />
    <OfficeID />
    <OfficeName />
    <DepartmentID />
    <DepartmentName />
  </Employee>
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
$output = '<Output />';
$warnings = array();
$errors = array();

# API logic - save message to the database
$id = $apiparams['id'] || 0;
if ($id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'Employee.MissingID', 'text' => 'Employee id is not provided') );
} else {
  use objEmployee;
  $objEmployee = new objEmployee($r); 
  $userInfo = $objEmployee->get_employee_info(('id' => $id));
  $output = $runtime->$r->txt->do_template($controller, 'API.Employee', $userInfo);
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
