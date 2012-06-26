<?php
use objVouchers;
$objVouchers = new objVouchers($r);

$id = lavnn('id', $_REQUEST, 0);

if (lavnn('name') == '' ) {
  $_SESSION['error'] = 'Please provide at least owners name');
} else {
  if ($id > 0) {
    $objVouchers->update_owner($_REQUEST);
  } else {
    # INSERT
    $id = $objVouchers->create_owner($_REQUEST);
    if ($id > 0) {
      $_SESSION['flash'] = 'New voucher owner is registered');
    } else {
      $_SESSION['error'] = 'Voucher owner could not be added');
    }
  }
}
go("?p=$module/home&tab=owners");

?>
