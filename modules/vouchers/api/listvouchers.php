<?php

use objVouchers;
$objVouchers = new objVouchers($r);

# Check if built-in parameter values are overloaded
$contenttype = $apiparams['_contenttype_'] || 'text/xml';
$charset = $apiparams['_charset_'] || 'utf-8';
$format = $apiparams['_format_'] || '';
 
# Initialize first-level variables that appear in API output 
$result = 'OK';
$output = '';
$warnings = array();
$errors = array();

$serie = $apiparams['serie'] || 0;
if ($serie == 0) {
  $result = 'ERR';
  push @errors, $runtime->hash2ref( ('code' => 'ListSerieVouchers.Failed', 'text' => 'Serie is invalid: empty or does not exist. ') );
} else {
  $vouchers = $objVouchers->list_serie_vouchers( ('serie' => $serie) );
  $output = $runtime->$runtime->txt->do_template($controller, 'api.ListSerieVouchers', array('vouchers' => $vouchers));
}

# Return resulting XML API output - quite similar to all APIs
print "content-type: $contenttype; charset=$charset;\n\n";
print $runtime->$runtime->txt->do_template($controller, 'Envelope', array(
  'result' => $result,
  'output' => $output,
  'warnings' => Arrays::a2xml($warnings, 'Warnings', 'Warning'),
  'errors' => Arrays::a2xml($errors, 'Errors', 'Error'),
));

1;

?>
