<?php


# Define help variable
$HELP = "
  MANDATORY
  gen_user_id   int
  
  OPTIONAL
  contract_number   int
  date_from         varchar
  date_to           varchar
  category          int
  
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
$contract_id = $apiparams['contract_number'] || '';
$date_from = $apiparams['date_from'] || '';

if ($gen_user_id == 0) {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetBalance.UnknownGenerator', 'text' => 'Unknown Generator not served') );
} else {
  $genUserInfo = $runtime->s2r($controller, 'GetGeneratorUserInfo', array('id' => $gen_user_id));
  if (count($genUserInfo) > 0) {
    delete $genUserInfo['password']; # Security, security, security...
    $generator_id = $genUserInfo['generator_id'];
    $sqlParams = (
      'lang' => $lang, 
      'generator_id' => $generator_id,
      'date_from' => $date_from,
      'date_to' => ($apiparams['date_to'] || ''),
      'contract_number' => $contract_id,
      'category' => ($apiparams['category'] || ''),
    );
  
    $generatorInfo = $runtime->s2r($controller, 'GetGeneratorInfo', $sqlParams);
    $genUserInfo['generatorinfo'] = $runtime->dotmod($controller, 'API.GetBalance.GeneratorInfo', $generatorInfo); 
    
    Arrays::r2xml($generatorInfo, 'GeneratorInfo');
    $categories = $runtime->s2a($controller, 'ListCategories', $sqlParams);
    $genUserInfo['categories'] = $categories;
  
    if ($date_from <> '') { 
      $balances = $runtime->s2a($controller, 'ListExpenseCurrBalances', $sqlParams); 
      $genUserInfo['balances'] = $balances;
      $currencies = $runtime->s2a($controller, 'ListExpenseCurrencies', $sqlParams);
      $genUserInfo['currencies'] = $currencies;
      $expenses = $runtime->s2a($controller, 'ListExpenses', $sqlParams);
      $genUserInfo['expenses'] = $expenses;
      $otherexpenses = $runtime->s2r($controller, 'CountOtherExpenses', $sqlParams);
      $genUserInfo['otherexpenses'] = $runtime->dotmod($controller, 'API.GetBalance.OtherExpenses', $otherexpenses); 
      $interc = $runtime->s2a($controller, 'Intercompany', $sqlParams);
      $genUserInfo['interc'] = $interc;
    }

    $output = $runtime->dotmod($controller, 'API.GetBalance', $genUserInfo);    
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'GetBalance.NotFound', 'text' => 'Generator data not found') );
  }
}

# Return resulting XML API output - quite similar to all APIs
print "content-type: $contenttype; charset=$charset;\n\n";
print $runtime->dotmod($controller, 'API.Envelope', array(
  'result' => $result,
  'output' => $output,
  'warnings' => Arrays::a2xml($warnings, 'Warnings', 'Warning'),
  'errors' => Arrays::a2xml($errors, 'Errors', 'Error'),
));

1;

?>
