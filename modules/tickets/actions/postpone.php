<?php
$id = lavnn('id');
if ($id > 0) {
  use objTicketing;
  $objT = new objTicketing($r);
  $objT->postpone_ticket($id);
}
$nextUrl = lavnn('nextUrl', $_REQUEST, '');
go($nextUrl);

1;
?>
