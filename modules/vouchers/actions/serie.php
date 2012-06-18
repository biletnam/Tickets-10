<?php

use objVouchers;
$objVouchers = new objVouchers($r);

$serie = lavnn('id');
if ($serie > 0) {
  $serieInfo = $runtime->s2r($module, 'GetVoucherSerieData', $_REQUEST); 
  #print Dumper($serieInfo);

  use ctlTab;
  $tabVoucherSerie = new ctlTab($r, 'ctVoucherSerie');

  $tabVoucherSerie->addTab('edit', dot('serie.tab.edit'), dot('serie.edit', $serieInfo)); 
  $vouchers = $objVouchers->list_serie_vouchers(('serie' => $serie)); 
  $serieInfo['vouchers'] = $vouchers;
  $tabVoucherSerie->addTab('vouchers', dot('serie.tab.vouchers'), dot('serie.vouchers', $serieInfo)); 
  $tabVoucherSerie->addTab('addvouchers', dot('serie.tab.addvouchers'), dot('serie.addvouchers', $serieInfo)); 
  $tabVoucherSerie->setDefaultTab(lavnn('tab') || 'vouchers');
  $serieInfo['tabcontrol'] = $tabVoucherSerie->getHTML();
  
  $page['js'] = dotmod('main', 'tabcontrol.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');

  $page->add('title',  $serieInfo['pagetitle'] = $runtime->doTemplate($module, 'title.serie', $serieInfo);
  $page->add('main', $runtime->doTemplate($module, 'serie', $serieInfo);
}



  
?>
