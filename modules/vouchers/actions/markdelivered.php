<?php
use objVouchers;
$objVouchers = new objVouchers($r);

$id = lavnn('id', $_REQUEST, 0);
$view = lavnn('view') || 'serie';
if ($id > 0) {
  $voucherInfo = $objVouchers->mark_delivered(('id' => $id));
  $voucherstock = $voucherInfo['stock'] || 0;
  $voucherserie = $voucherInfo['serie'] || 0;
  $penultimate_location_type = $voucherInfo['penultimate_location_type'] || '';
  $penultimate_location_id = $voucherInfo['penultimate_location_id'] || 0;
  if ($view == 'stock' && $voucherstock > 0) {
    go("?p=vouchers/stock&tab=vouchers&id=$voucherstock");
  } elseif ($view == 'writtenoff' && $penultimate_location_type == 'stock' && $penultimate_location_id > 0) {
    go("?p=vouchers/stock&tab=writtenoff&id=$penultimate_location_id");
  } elseif ($voucherserie > 0) {
    go("?p=vouchers/serie&tab=vouchers&id=$voucherserie");
  } 
}

$canManageVouchers = $r['userInfo']['additionalData']['can_manage_vouchers'] || 0; 
if ($acc['superadmin'] || $canManageVouchers > 0) {
  go("?p=$module/home&tab=providers");
} else {
  go("?p=$module/employeevouchers");
}


?>
