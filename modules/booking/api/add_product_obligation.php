<?php

# Define help variable
$HELP = "
  MANDATORY
  product_id       int
  contract_no      int
  code             char(3)
  qty              int

  OPTIONAL
  template_id         int
  date_from           varchar(10) DD.MM.YYYY
  date_to             varchar(10) DD.MM.YYYY
  periods             int
  is_breakable        tinyint  default = 1
  is_periodical       tinyint  default = 0
  is_prioritized      tinyint  default = 0
  requires_maintfees  tinyint  default = 0
  season              tinyint
  apt_type_id         tinyint
  for_year            int
  active              tinyint  default = 0
                
  SUCCESSFUL 
  <ProductObligation>
    <ID />
  </ProductObligation>
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

# API logic 
$product_id = $apiparams['product_id'] || '';
$contract_no = $apiparams['contract_no'] || '';
$date_from = $apiparams['date_from'] || '';
$date_to = $apiparams['date_to'] || '';
$code = $apiparams['code'] || '';
$qty = $apiparams['qty'] || '';
$is_breakable = $apiparams['is_breakable'] || '';
$season = $apiparams['season'] || '';
$year = $apiparams['year'] || '';
if ($contract_no == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'AddProductObligation.MissingContractNumber', 'text' => 'Contract number is not provided') );
} elseif ($code == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'AddProductObligation.MissingCode', 'text' => 'Obligation code is not provided') );
} elseif ($qty == '') {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'AddProductObligation.MissingQty', 'text' => 'Quantity is not provided') );
} else {
  # Warn about missing optional parameters
  push @warnings, $runtime->hash2ref( ('code' => 'AddProductObligation.MissingProductID', 'text' => 'ProductID is not provided') ) if ($product_id == ''); 
  push @warnings, $runtime->hash2ref( ('code' => 'AddProductObligation.MissingDateFrom', 'text' => 'DateFrom is not provided') ) if ($date_from == ''); 
  push @warnings, $runtime->hash2ref( ('code' => 'AddProductObligation.MissingDateTo', 'text' => 'DateTo is not provided') ) if ($date_to == ''); 
  push @warnings, $runtime->hash2ref( ('code' => 'AddProductObligation.MissingAptType', 'text' => 'Apartment Type is not provided') ) if ($apt_type == ''); 
  push @warnings, $runtime->hash2ref( ('code' => 'AddProductObligation.MissingSeason', 'text' => 'Season is not provided') ) if ($season == '' && code == 'INT'); 
  push @warnings, $runtime->hash2ref( ('code' => 'AddProductObligation.MissingYear', 'text' => 'Year is not provided') ) if ($year == ''); 
  # Add obligation to the database
  $id = $runtime->sid($controller, 'AddProductObligation', $apiparams);
  if ($id > 0) {
    $output = $runtime->$r->txt->do_template($controller, 'API.AddProductObligation', array('id' => $id));
  } else {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'AddProductObligation.Failure', 'text' => 'Could not complete INSERT operation:' . $runtime->spreview($controller, 'AddProductObligation', $apiparams)) );
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
