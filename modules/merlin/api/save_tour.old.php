<?php

# Define help variable
$HELP = "
  MANDATORY
  office_id
  team_id
  name_id           (Merlin id)
  tour_id
  client_name
  client_surname
  country
  nationality
  language
  age_group
  marital_status
  address_line1
  address_line2
  address_line3
  city
  state
  country
  telephone_country
  dial_code
  number
  email
  tourdate
  tour_start_time
  tour_end_time
  client_qualification
  generator_id
  toursource
  opc_captain_manager
  tm_captain_manager
  confirmer
  surveyor_captain_manager
  rep_to_man
  presales_comment
  aftersales_comment
  no_deal_reason
  no_deal_comment
  
  SUCCESSFUL 
  <ID />
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
use objMerlin;
$objM = new objMerlin($r);
$id = $objM->save_tour(%_REQUEST);
if ($id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'SaveTour.Failed', 'text' => 'Could not add tour') );
} else {
  $output = $runtime->$runtime->txt->do_template($controller, 'API.SaveTour', array('id' => $id));
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
