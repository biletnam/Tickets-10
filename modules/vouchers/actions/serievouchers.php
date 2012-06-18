<?php

use objVouchers;
$objVouchers = new objVouchers($r);

$serie = lavnn('id');
if ($serie > 0) {
  $serieInfo = $runtime->s2r($module, 'GetVoucherSerieData', $_REQUEST); 
  $vouchers = $objVouchers->list_serie_vouchers(('serie' => $serie)); 
  $serieInfo['vouchers'] = $vouchers;
  $page->add('title',  $serieInfo['pagetitle'] = $runtime->doTemplate($module, 'title.vouchers', $serieInfo);
  $page->add('main', $runtime->doTemplate($module, 'vouchers', $serieInfo);
}



  
?>
