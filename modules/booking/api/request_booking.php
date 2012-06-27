<?php

# Define help variable
$HELP = "
  MANDATORY
  obligation_id     int
  contract_id       int
  hotel_id          int
  date_from         varchar(10) e.g. '21.05.2011'
  date_to           varchar(10) e.g. '28.05.2011'
  apt_type          varchar(2) { 'T0' || 'T1' || 'T2' || 'T3' }
  lang_id           int { 1=English (default) | 2=Russian }

  OPTIONAL WITH WARNING
  fname1            varchar
  lname1            varchar
  passport1         varchar
  job1              varchar
  rel1              int from classifier
  fname2            varchar
  lname2            varchar
  passport2         varchar
  job2              varchar
  rel2              int from classifier
  morepax           int

  OPTIONAL IF morepax is not given, where i between 1 and morepax
  morepeople_i_fname       varchar
  morepeople_i_lname       varchar
  morepeople_i_rel         varchar
  morepeople_i_job         varchar
  morepeople_i_passport    varchar
  morepeople_i_dob         varchar
  
  SUCCESSFUL
  <BookingRequest>
    <ID />
    <HotelID />
    <HotelName />
    <AptType />
    <Arrival />
    <Departure />
    <FirstName1 />
    <LastName1 />
    <Nationality />
    <Passport1 />
    <DateBirth1 />
    <MaritalStatus1 />
    <Occupation1 />
    <PhoneMobile />
    <PhoneHome />
    <PhoneOffice />
    <FirstName2 />
    <LastName2 />
    <Passport2 />
    <DateBirth2 />
    <MaritalStatus2 />
    <Occupation2 />
    <Guests>
      <morepeople>
        <person>
          <fname />
          <lname />
          <rel />
          <job />
          <passport />
          <dob />
        </person>
      </morepeople>
    </Guests>
  </BookingRequest>  
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

$moreguests = array(); # array for guests' data

# Parse guests data from the request. In case of weird data, return errors
$morepax = $apiparams['morepax'] || 0;
if ($morepax > 0) {
  for ($i = 1; $i <= $morepax; $i++) {   
    $g = array(
      'fname' => $apiparams{"morepeople_$i_fname"} || '',
      'lname' => $apiparams{"morepeople_$i_lname"} || '',
      'rel' => $apiparams{"morepeople_$i_rel"} || '',
      'job' => $apiparams{"morepeople_$i_job"} || '',
      'passport' => $apiparams{"morepeople_$i_passport"} || '',
      'dob' => $apiparams{"morepeople_$i_dob"} || '',
    );
    push @moreguests, $g;
  }
}

# API logic - check contract and other inputs
if ($apiparams['obligation_id'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'RequestBooking.InvalidInputParams', 'text' => 'Obligation ID needed') );
} elseif ($apiparams['contract_id'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'RequestBooking.InvalidInputParams', 'text' => 'Contract number is not provided') );
} elseif ($apiparams['hotel_id'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'RequestBooking.InvalidInputParams', 'text' => 'Resort is not provided') );
} elseif ($apiparams['date_from'] == '' || $apiparams['date_to'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'RequestBooking.InvalidInputParams', 'text' => 'Arrival and departure dates are not provided') );
} elseif ($apiparams['apt_type'] == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'RequestBooking.InvalidInputParams', 'text' => 'Apartment type is not provided') );
} else {

  # check for optional fields
  $firstGuestsDataMissing = 0;
  if ($apiparams['fname1'] == '' || $apiparams['lname1'] == '' || $apiparams['passport1'] == '' || $apiparams['job1'] == '' || $apiparams['rel1'] == '') {
    $firstGuestsDataMissing++;
    push @warnings, $runtime->hash2ref( ('code' => 'RequestBooking.InvalidInputParams', 'text' => 'Some data about first guest is missing') );
  } 
  if ($apiparams['fname2'] == '' || $apiparams['lname2'] == '' || $apiparams['passport2'] == '' || $apiparams['job2'] == '' || $apiparams['rel2'] == '') {
    $firstGuestsDataMissing++;
    push @warnings, $runtime->hash2ref( ('code' => 'RequestBooking.InvalidInputParams', 'text' => 'Some data about second guest is missing') );
  } 
  if ($apiparams['homephone'] == '' && $apiparams['workphone'] == '' && $apiparams['mobilephone'] == '') {
    $firstGuestsDataMissing++;
    push @warnings, $runtime->hash2ref( ('code' => 'RequestBooking.InvalidInputParams', 'text' => 'At least one phone number should be provided') );
  }
  
  # file the request
  use objBooking;
  $objB = new objBooking($r);
  # Try to save the request. SQL will return dataset with "err" field if it will not succeed
  $sqlResult = $objB->save_booking_request_from_client(%apiparams);
  if (count($sqlResult) == 0) {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'RequestBooking.UnknownFailure', 'text' => 'Unknown failure while saving a request') );
  } elseif($sqlResult['err'] <> '') {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'RequestBooking.Failure', 'text' => 'Saving booking request failed with message: ' . $sqlResult['err']) );
  } else {
    # Now, when skeleton for booking request is done, let's add guests' data
    $id = $apiparams['bookreq_id'] = $sqlResult['id'];
    if ($firstGuestsDataMissing == 0) {
      $objB->set_booking_request_first_guests(%apiparams);
      if (count($moreguests) > 0) {
        $objB->set_booking_request_more_guests($id, $moreguests);
      }
    }
    # Return the request in the same form as it was written to DB
    $bookingRequest = $objB->get_booking_request(('id' => $id));
    $output = $runtime->$r->txt->do_template($controller, 'API.RequestBooking', $bookingRequest);
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
