<?php

# Define help variable
$HELP = "

  Deletes one employee contact

  MANDATORY
  id    int
  
  SUCCESSFUL - same output as for employeecontacts
  <Contacts>
    <Contact>
      <ID />
      <SeqNo />
      <IsPublic />
      <ContactGroup />
      <ContactType />
      <ContactValue />
    </Contact>
  </Contacts>
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
$id = $apiparams['id'];
if ($id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'DeleteEmployeeContact.MissingID', 'text' => 'Contact id is not provided') );
} else {
  $contactInfo = $runtime->s2r($controller, 'GetEmployeeContact', array('id' => $id));
  $employee = $contactInfo['user_id'];
  if ($employee > 0 && $contactInfo['user_type'] == 'U') {
    use objEmployee;
    $objEmployee = new objEmployee($r); 
    $opresult = $objEmployee->delete_contact(('id' => $id));
    if ($opresult > 0) {
      $employeeContacts = $objEmployee->get_employee_contacts(('id' => $employee));
      $output = $runtime->$runtime->txt->do_template($controller, 'API.EmployeeContacts', array('contacts' => $employeeContacts));
    }
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'DeleteEmployeeContact.Failure', 'text' => 'Could not delete contact') );
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
