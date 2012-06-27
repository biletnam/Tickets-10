<?php

# Define help variable
$HELP = "
  MANDATORY
  gen_user_id   int
  contract_id   int
  
  SUCCESS RESULTS
  <Contracts>
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
$contract_id = $apiparams['contract_id'] || 0;
if ($gen_user_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetContract.UnknownGenerator', 'text' => 'Unknown Generator not served') );
} elseif($contract_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetContract.UnknownContract', 'text' => 'Contract not specified') );
}else {
  $genUserInfo = $runtime->s2r($controller, 'GetGeneratorUserInfo', array('id' => $gen_user_id));
  if (count($genUserInfo) > 0) {
    $generator_id = $genUserInfo['generator_id'];
    $genInfo = $runtime->s2r($controller, 'GetGeneratorInfo', array('generator_id' => $generator_id));
    $sqlParams = array(
      'contract_numbers' => $contract_id,
      'generator_id' => $generator_id,
    );
    delete $genUserInfo['password']; # Security, security, security...
    $contractInfo = $runtime->s2a($controller, 'GetSalesReport', $sqlParams); 
    $firstentry = $contractInfo[0];
    $entries = array();
    foreach $c (@contractInfo) {
      $payments = $runtime->s2a($controller, 'ListContractPayments', array('id' => $contract_id));
      $giftrows = $runtime->s2a($controller, 'ListContractGifts', array('id' => $contract_id));
      $c['payments'] = $payments;
      $c['giftrows'] = $giftrows;
      push @entries, $runtime->$r->txt->do_template($controller, 'API.GetContract.listitem', ${$c});
    }
    $genUserInfo['contracts'] = $contractInfo;
    if ($genInfo['show_fu_comments'] == 1) {
      $fucomments = $runtime->s2a($controller, 'ListFollowUpComments', $sqlParams); 
      $genUserInfo['fu_comments2'] = $fucomments;
      $genUserInfo['fu_comments'] = Arrays::a2xml($fucomments, 'FUComments', 'FUComment');
    }
    $firstentry = $contractInfo[0];

    $output = $runtime->$r->txt->do_template($controller, 'API.GetContract', $genUserInfo);    
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'GetContract.NotFound', 'text' => 'Member data was not found') );
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
