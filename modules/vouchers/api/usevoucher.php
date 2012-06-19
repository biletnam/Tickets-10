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

$serie = $apiparams['serie'] || '';
$client_id = $apiparams['client_id'] || '';
$user_id = $apiparams['user_id'] || '';
if ($serie <> '' && $client_id <> '') {
  $voucherInfo = $objVouchers->use_voucher(('serie' => $serie, 'user_id' => $r['userID'], 'client_id' => $client_id)); 
  if (count($voucherInfo) == 0 || $voucherInfo['error'] <> '') {
    $result = 'ERR'; 
    push @errors, $runtime->hash2ref( ('code' => 'UseVoucher.Failed', 'text' => 'Voucher could not be used. ' . $voucherInfo['error']) );
  } else {
    $output = $runtime->$runtime->txt->do_template($controller, 'api.UseVoucher', $voucherInfo);
  }
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'UseVoucher.InvalidInputParams', 'text' => 'Either voucher serie or client id or both are not provided') );
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
