<?php

# Define help variable
$HELP = "
  INPUTS
  MarketingApiId
  NameType
  NameID
  Title
  FirstName
  LastName
  HomeCountry
  Nationality
  Language
  DateOfBirth
  MaritalStatus
  AddressLine1
  AddressLine2
  AddressLine3
  City
  State
  Country
  TelephoneCountry
  DialCode
  TelNumber
  Email
  AppointmenBookerId
  Team
  Venue
  TourStartTime
  TourEndTime
  TourStatus
  Toured
  Qualified
  Show
  Cancelled
  Confirmed
  Sale
  NQReasonId
  NQReason
  NDReasonId
  NDReason
  PreTourNoteCategory
  PreTourNoteSubject
  PreTourNotes
  InTourNoteCategory
  InTourNoteSubject
  InTourNotes
  PostTourNoteCategory
  PostTourNoteSubject
  PostTourNotes
  CommissionCategory
  Booker
  BookerNameId
  BookerExtRef1
  PublicationSourceId
  Campaign
  Communication
  Media
  RStat
  Agent1
  Agent1Cat
  Agent2
  Agent2Cat
  Agent3
  Agent3Cat
  Agent4
  Agent4Cat
  Agent5
  Agent5Cat
  Agent6
  Agent6Cat
  APIStatus
  APIStatusDate  
  CommissionCategoryId
  CommunicationId
  CostCurrencyId
  Cost
  CostItems
  AppQualificationStatusId
  SourceCategoryId
  SourceNameId
  SourceFirstName
  SourceLastName
  ResReqCreatedById
  ResReqCreatedBy
  ResReqSourceId
  ResReqSource
  ResReqAlocatedUserId
  ResReqAlocatedUser
     
  OUTPUT 
  <ID /> 
  <AbsoluteClientID />
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
$id = $objM->save_tour(%apiparams); # Also calls save_tour_cost_items behind the scenes if required.
if ($id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'SaveTour.Failed', 'text' => 'Could not add tour : ' . $objM->get_last_error()) );
} else {
  # Return output
  $tourInfo = $runtime->s2r($controller, 'GetTourInfo', array('id' => $id));
  $output = $runtime->$r->txt->do_template($controller, 'API.SaveTour', $tourInfo);
  $warning = $objM->get_last_warning(); 
  push @warnings, $runtime->hash2ref( ('code' => 'SaveTour.ImportFailed', 'text' => 'Tour added but not imported; ' . $warning) ) if ($warning <> '');
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
