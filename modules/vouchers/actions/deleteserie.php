<?php

use objVouchers;
$objVouchers = new objVouchers($r);

$serie = lavnn('id', $_REQUEST, 0);
if ($serie > 0) {
  $vouchers = $objVouchers->list_serie_vouchers(('serie' => $serie)); 
  if (count($vouchers) > 0) {
    set_cookie('error', "Only series without vouchers can be deleted");
  } else {
    srun($module, 'DeleteVoucherSerie', $_REQUEST);
  }
}
go("?p=$module/home&tab=series");

?>
