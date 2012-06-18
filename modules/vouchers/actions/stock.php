<?php

use objVouchers;
$objVouchers = new objVouchers($r); 

$stock = lavnn('id');
if ($stock > 0) {
  $stockInfo = $runtime->s2r($module, 'GetVoucherStockData', $_REQUEST); 
#  print Dumper($stockInfo);

  if (count($stockInfo) > 0) {
    # get vouchers that reside in this stock
    $vouchers = $objVouchers->list_stock_vouchers(('stock' => $stock)); 
    $stockInfo['vouchers'] = $vouchers;

    # check access
    $access = 'none';
    if ($acc['superadmin'] == 1) {
      $access = 'edit';
    } elseif ($acc->check_resource("editvoucherstock:$stock", $r['userID'])) {
      $access = 'edit';
    } elseif ($acc->check_resource("readvoucherstock:$stock", $r['userID'])) {
      $access = 'read';
    }
    $runtime->saveMoment('  access checked with result: '.$access);  
    # render the stock depending access level
    if ($access == 'none') {
      $page->add('main', $runtime->doTemplate($module, 'stock.noaccess');
    } else {
      use ctlTab;
      $tabVoucherStock = new ctlTab($r, 'ctVoucherStock');
      $tabVoucherStock->addTab('vouchers', dot('stock.tab.vouchers'), dot('stock.vouchers', $stockInfo)); 
      # TODO: show history of written off vouchers
      $writtenoff = $runtime->s2a($module, 'ListWrittenOffVouchers', array('stock' => $stock));
      if (count($writtenoff) > 0) {
        $stockInfo['writtenoff'] = $writtenoff;
        $tabVoucherStock->addTab('writtenoff', dot('stock.tab.writtenoff'), dot('stock.writtenoff', $stockInfo)); 
      }
      $tabVoucherStock->addTab('edit', dot('stock.tab.edit'), dot('stock.edit', $stockInfo)) if ($access == 'edit'); 
      $tabVoucherStock->addTab('readaccess', dot('stock.tab.readers', $articleInfo), dot('stock.readers', $stockInfo)) if ($access == 'edit');
      $tabVoucherStock->addTab('editaccess', dot('stock.tab.editors', $articleInfo), dot('stock.editors', $stockInfo)) if ($access == 'edit');
      $tabVoucherStock->setDefaultTab(lavnn('tab') || 'vouchers');
      $stockInfo['tabcontrol'] = $tabVoucherStock->getHTML();
      $page['js'] = dotmod('main', 'tabcontrol.js');
      $page['js'] .= dotmod('main', 'linkpeople.js');
      $page->add('css',  dotmod('main', 'tabcontrol.css');
      $page->add('css',  dotmod('main', 'linkpeople.css');
      $page->add('title',  $stockInfo['pagetitle'] = $runtime->doTemplate($module, 'title.stock', $stockInfo);
      $page->add('main', $runtime->doTemplate($module, 'stock', $stockInfo);
    }
    $runtime->saveMoment('  stock page constructed');  
  }
}



  
?>
