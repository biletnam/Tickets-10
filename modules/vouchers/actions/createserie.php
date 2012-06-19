<?php
use objVouchers;
$objVouchers = new objVouchers($r);

if (lavnn('name') == '' || lavnn('code') == '' ) {
  set_cookie('error', 'Please provide value for name, and code');
} else {
  $id = $objVouchers->create_serie(%_REQUEST); 
  if ($id > 0) {
    # TODO: add tags if they were provided!
    $tags = lavnn('tags', $_REQUEST, '');
    if ($tags <> '') {
      $tt = split(',', $tags);
      foreach $t (@tt) {
        $objVouchers->add_serie_tag(('serie' => $id, 'fulltag' => trim($t)));
      }
    }
    $_SESSION['flash'] = 'New voucher serie is registered, please proceed with adding vouchers');
    go("?p=$module/serie&tab=addvouchers&id=$id");
  } else {
    set_cookie('error', 'Voucher serie could not be added');
  }
}
go("?p=$module/home&tab=series");

?>
