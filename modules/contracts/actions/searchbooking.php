<?php

set_cookie('booking_search_source_type', 'contract');
set_cookie('booking_search_source_id', lavnn('contract'));

$contractInfo = $runtime->s2r($module, 'GetContractData', array('contract_no' => lavnn('contract')));
$client_name = $contractInfo['last_name'];
$contract_date = $contractInfo['Date_Contract'];
$location_id = $contractInfo['location_id'];
$contract_date = DateChemistry::Format(DateChemistry::CDate($contract_date), 'fulldate') if $contract_date <> '';
use Calendar;
$contract_date = Calendar::addDate($contract_date, -15) if $contract_date <> '';
go("?p=bookings/search&client_name=$client_name&datetype=date_from&datefrom=$contract_date&location_id=$location_id");

?>
