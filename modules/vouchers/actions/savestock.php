<?php
use objVouchers;
$objVouchers = new objVouchers($r);

$id = lavnn('id', $_REQUEST, 0);

if (lavnn('name') == '' ) {
  set_cookie('error', 'Please provide stock name');
} else {
  if ($id > 0) {
    $objVouchers->update_stock(%_REQUEST); 
  } else {
    # INSERT
    $id = $objVouchers->create_stock(%_REQUEST);
    if ($id > 0) {
      $_SESSION['flash'] = 'New voucher stock is registered');
    } else {
      set_cookie('error', 'Voucher stock could not be added');
    }
  }
}
go("?p=$module/home&tab=stock");

?>
