<?php
use objVouchers;
$objVouchers = new objVouchers($r);

$id = lavnn('id', $_REQUEST, 0);

if (lavnn('name') == '' ) {
  $_SESSION['error'] = 'Please provide at least provider name');
} else {
  if ($id > 0) {
    $id = $objVouchers->update_provider($_REQUEST);
  } else {
    # INSERT
    $id = $objVouchers->create_provider($_REQUEST);
    if ($id > 0) {
      $_SESSION['flash'] = 'New voucher provider is registered');
    } else {
      $_SESSION['error'] = 'Voucher provider could not be added');
    }
  }
}
go("?p=$module/home&tab=providers");

?>
