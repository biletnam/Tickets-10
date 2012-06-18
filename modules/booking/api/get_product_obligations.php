<?php

# Define help variable
$HELP = "
  MANDATORY
  contract_no       int

  SUCCESSFUL OUTPUT
  <Product>
    <ContractNumber />
    <ContractStatus />
    <ProvisionalObligations>
      <ProvisionalObligation>
        <ID />
        <Code />
        <Qty />
        <IsBreakable />
        <IsPrioritized />
        <IsPeriodical />
        <RequiresMaintenanceFees />
        <HighSeason />
        <Periods />
        <AptType />
      </ProvisionalObligation>
    </ProvisionalObligations>
    <Obligations>
      <Obligation>
        <ID />
        <DateFrom />
        <DateTo />
        <Code />
        <Qty />
        <QtyUsed />
        <IsBreakable />
        <IsPrioritized />
        <IsPeriodical />
        <RequiresMaintenanceFees />
        <HighSeason />
        <AptType />
        <ForYear />
        <Active />
      </Obligation>
    </Obligations>
    <UsedHistory>
      <Usage>
        <Obligation />
        <QtyUsed />
        <Booking />
      </Usage>
    </UsedHistory>
    <Gifts>
      <Gift>
        <Gift>
          <GiftNumber />
          <GiftCode />
          <GiftType />
          <Description />
          <Commentary />
          <UseGpcDeduction />
          <GpcDeduction />
          <OriginalDeduction />
          <Expense>
            <ExpenseDate />
            <Amount />
            <Currency />
            <EuroEquiv />
          </Expense>
        </Gift>
      </Gift>
    </Gifts>
  </Product>
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
$contract_no = $apiparams['contract_no'] || '';

if ($contract_no <> '') {
  
  $contractDetails = $runtime->s2r($controller, 'GetContractDetails', array('id' => $contract_no));
  $status = $apiparams['status_name'] = $contractDetails['status_name'] || '';
  if ($status == 'COMPLETED') {
    $obligations = $runtime->s2a($controller, 'GetProductObligations', $apiparams);
    $apiparams['obligations'] = $obligations;
    if (count($obligations) > 0) {
      $ids = $apiparams['obligation_ids'] = Arrays::join_column(',', 'id', $obligations);
      $usages = $runtime->s2a($controller, 'GetProductUsages', $apiparams);
      $apiparams['usages'] = $usages;
    }
  } else {
    $provisionalobligations = $runtime->s2a($controller, 'GetProductProvisionalObligations', $contractDetails);
    $apiparams['provisionalobligations'] = $provisionalobligations;
  }
  $gifts = $runtime->s2a($controller, 'GetContractGifts', $apiparams);
  $apiparams['gifts'] = $gifts;
    
  $output = $runtime->dotmod($controller, 'API.ProductObligations', $apiparams);
} else {
  $result = 'ERR'; 
  push @errors, $runtime->hash2ref( ('code' => 'GetProductObligations.InvalidInputParams', 'text' => 'Contract number is not provided') );
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
