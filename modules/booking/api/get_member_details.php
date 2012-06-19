<?php

# Define help variable
$HELP = "
  ONE OF TWO IS MANDATORY
  client_id     int
  contract_id   int
  
  SUCCESS RESULTS
  <MemberDetails>
    <ID />
    <MerlinID />
    <FirstName />
    <LastName />
    <Email> />
    <MemberType />
    <AddressLine1 /> 
    <AddressLine2 /> 
    <AddressLine3 />
    <City />
    <Postcode />
    <CountryID />
    <CountryEnglishName />
    <CountryRussianName />
    <CountryChineseName />
    <PhoneMobile />
    <PhoneHome />
    <PhoneOffice />
    <LanguageName />
    <LanguageGroupName />
    <MaritalStatus />
    <IncomeRange />
    <DateBirth />
    <Passport />
    <SpouseFirstName />
    <SpouseLastName />
    <SpouseDateBirth />
    <SpousePassport />
    <Contracts>
      <Contract>
        <ContractNumber />
        <ContractDate />
        <CancelledDate />
        <CompletedDate />
        <StatusCode />
        <StatusName />
        <CurrencyCode />
        <OutstandingBalance />
        <ResortCode />
        <ResortName />
        <ContractType />
        <ProductType />
        <NumberOfWeeks />
        <AptType />
        <AptNo />
        <FirstYear />
        <ContractResortNumber />
        <BookingID />
        <PeriodCode />
        <PeriodType />
        <MemberStatus />
        <UpgradedFrom />
        <UpgradedTo />
      </Contract>
    </Contracts>
    <Bookings>
      <Booking>
        <RefNo />
        <Status />
        <DateFrom />
        <DateTo />
        <HotelName />
        <ArrivalTransfer />
        <ArrivalFlight />
        <ArrivalTime />
        <DepartureTransfer />
        <DepartureFlight />
        <DepartureTime />
        <Cancel />
        <CancelReason />
        <CancelDate />
      </Booking>
    </Bookings>
    <BookingRequests>
      <BookingRequest>
        <DateFrom />
        <ArrivalTransfer />
        <DateTo />
        <DepartureTransfer />
        <HotelID />
        <HotelName />
        <FirstGuest />
        <SecondGuest />
      </BookingRequest>
    </BookingRequests>
  </MemberDetails>
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

# API logic - check username and password
$client_id = $apiparams['client_id'] || '';
$contract_id = $apiparams['contract_id'] || '';
if ($client_id == '' && $contract_id <> '') {
  $contractInfo = $runtime->s2r($controller, 'GetClientByContract', $apiparams);
  if (count($contractInfo) > 0) {
    $client_id = $apiparams['client_id'] = $contractInfo['client_id'];
  }
}
if ($client_id <> '') {
  $memberInfo = $runtime->s2r($controller, 'GetMemberDetails', $apiparams);
  if (count($memberInfo) > 0) {
    $bookingrequests = $runtime->s2a($controller, 'ListMemberBookingRequests', $apiparams);
    #die Dumper($bookingrequests);
    $memberInfo['bookingrequests'] = $bookingrequests;
    $bookings = $runtime->s2a($controller, 'ListMemberBookings', $apiparams);
    #die Dumper($bookings);
    $memberInfo['bookings'] = $bookings;
    $contracts = $runtime->s2a($controller, 'ListMemberContracts', $apiparams);
    #die Dumper($contracts);
    $memberInfo['contracts'] = $contracts;
    $output = $runtime->$runtime->txt->do_template($controller, 'API.MemberDetails', $memberInfo);
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'GetMemberDetails.NotFound', 'text' => 'Member data was not found') );
  }
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetMemberDetails.InvalidInputParams', 'text' => 'Client ID was not provided (or could not be found from contract ID') );
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
