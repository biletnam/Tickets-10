<?php

# Define help variable
$HELP = "
  MANDATORY
  gen_user_id   int
  
  OPTIONAL
  first_gen_id  int
  office_id     int
  date_from     varchar
  date_to       varchar
  status        int
  
  SUCCESS RESULTS
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
";

# Return HELP string if it is requested
use HTML::Entities;
if (exists $apiparams['_help_']) {
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

# API logic
$gen_user_id = $apiparams['gen_user_id'] || 0;
if ($gen_user_id > 0) {
  $genUserInfo = $runtime->s2r($controller, 'GetGeneratorUserInfo', array('id' => $gen_user_id));
  if (count($genUserInfo) > 0) {
    $generator_id = $genUserInfo['generator_id'];
    use Calendar;
    $todayArr = Calendar::getTodayArr();
    $first_gen_id = $apiparams['first_gen_id'] || '';
    $office_id = $apiparams['office_id'] || '';
    $date_to = $apiparams['date_to'] || $todayArr['fulldate'];
    $date_from = $apiparams['date_from'] || Calendar::addDate($date_to, -30);
    $sqlParams = array(
      'generator_id' => $generator_id,
      'first_gen_id' => $first_gen_id, 
      'office_id' => $office_id,
      'date_from' => $date_from,
      'date_to' => $date_to,
      'status' => ($apiparams['status'] || '')
    );
    delete $genUserInfo['password']; # Security, security, security...
    #$genUserInfo['query'] = encode_entities($runtime->spreview($controller, 'ListContracts', $sqlParams));
    $genUserInfo['generatorinfo'] = Arrays::r2xml($genUserInfo, 'GeneratorInfo');
    $genUserInfo['parameters'] = Arrays::r2xml($sqlParams, 'Parameters'); 
    $contracts = $runtime->s2a($controller, 'ListContracts2', $sqlParams);
    $genUserInfo['contracts'] = $contracts;
    $offices = $runtime->s2a($controller, 'ListContractOffices', $sqlParams);
    $genUserInfo['offices'] = $offices;   
    $generators = $runtime->s2a($controller, 'ListGenerators', $sqlParams);
    $genUserInfo['generators'] = $generators;   
    
    $output = $runtime->$runtime->txt->do_template($controller, 'API.ListContracts', $genUserInfo);    
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'ListContracts.NotFound', 'text' => 'Member data was not found') );
  }
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'ListContracts.UnknownGenerator', 'text' => 'Unknown Generator not served') );
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
