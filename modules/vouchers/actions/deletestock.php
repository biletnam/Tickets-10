<?php

use objVouchers;
$objVouchers = new objVouchers($r);

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $objVouchers->delete_stock($_REQUEST);
}
go("?p=$module/home&tab=stock");

?>
