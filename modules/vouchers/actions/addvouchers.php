<?php
use objVouchers;
$objVouchers = new objVouchers($r);

if(lavnn('serie') == '' ) {
  $_SESSION['error'] = 'Serie information was lost');
  go("?p=$module/home&tab=series");
} elseif ((lavnn('references') == '' && lavnn('qty') == '') || lavnn('doc_location_type') == '' || lavnn('doc_location_id') == '' || lavnn('owner_type') == '' || lavnn('owner_id') == '') {
  $_SESSION['error'] = 'Please provide values for location, owner and quantity');
} else {
  $result = $objVouchers->add_serie_vouchers($_REQUEST); 
  if ($result['cnt_success'] > 0) {
    $_SESSION['flash'] = $result['cnt_success'].' vouchers are added to the serie');
  } 
  if ($result['cnt_failure'] > 0) {
    $_SESSION['error'] = $result['cnt_failure'].' vouchers are not added');
  }
}
go("?p=$module/serie&tab=vouchers&id=".lavnn('serie'));

?>
