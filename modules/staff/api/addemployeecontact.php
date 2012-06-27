<?php

# Define help variable
$HELP = "

  Adds another contact to employee

  MANDATORY
  employee    int
  value       varchar

  type_id     int
  OR both:  
  group       varchar
  type        varchar
  
  SUCCESSFUL 
  <NewContact>
    <ID />
  </NewContact>
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
$employee = $apiparams['employee'] || 0;
$contact_type_id = $apiparams['type_id'];
$contact_group = $apiparams['group']; 
$contact_type = $apiparams['type'];
$contact_value = $apiparams['value'];
if ($contact_type_id == '') {
  # Contact type was not given explicitly, so let's try to find it out from implicit parameters
  if ($contact_group == '' || $contact_type == '') {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'AddEmployeeContact.MissingInfo', 'text' => 'Contact group or type is not provided') );
  } else {
    $contactTypeInfo = $runtime->s2r($controller, 'LookupContactType', array('type' => $contact_group, 'code' => $contact_type));
    if (count($contactTypeInfo) > 0) {
      $contact_type_id = $contactTypeInfo['id'];
    } else {
      $result = 'ERR'; 
      push @errors, $runtime->hash2ref( ('code' => 'AddEmployeeContact.MissingInfo', 'text' => 'Invalid contact group or type provided') );
    }
  }
}

if ($employee == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'AddEmployeeContact.MissingEmployee', 'text' => 'Employee id is not provided') );
}  elseif ($contact_value == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'AddEmployeeContact.MissingInfo', 'text' => 'Contact value is not provided') );
} elseif ($contact_type_id > 0) {
  $id = $runtime->sid($controller, 'AddEmployeeContact', array(
    'user_id' => $employee, 
    'type_id' => $contact_type_id, 
    'value' => $contact_value,
    'created_by' => $r['userID'], # TODO rework, because this makes no sence for API, cause it is anonymous
  ));
  if ($id > 0) {
    $output = $runtime->$r->txt->do_template($controller, 'API.AddEmployeeContact', array('id' => $id));
    # Also, sync back, it will process all special cases for some contact types
    if ($contact_group == 'email' && $contact_type == 'office') {
      use objEmployee;
      $objEmp = new objEmployee($r);
      $objEmp->sync_contact(('id' => $id));
    }
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'AddEmployeeContact.Failure', 'text' => 'Could not create a new contact') );
  }
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
