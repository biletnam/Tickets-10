<?php
use objVouchers;
$objVouchers = new objVouchers($r);

$id = lavnn('id');
if ($id <> '') {

  if (lavnn('name') == '' || lavnn('code') == '' ) {
    set_cookie('error', 'Please provide value for name and code');
    go("?p=$module/serie&tab=edit&id=$id");
  } else {
    $objVouchers->save_serie(%_REQUEST); 
    # TODO: add tags if they were provided!
    $tags = lavnn('tags', $_REQUEST, '');
    if ($tags <> '') {
      $tt = split(',', $tags);
      foreach $t (@tt) {
        $objVouchers->add_serie_tag(('serie' => $id, 'fulltag' => trim($t)));
      }
    }
    set_cookie('flash', 'Voucher serie date is updated');
    go("?p=$module/serie&tab=vouchers&id=$id");
  }
}

go("?p=$module/home&tab=series");

?>
