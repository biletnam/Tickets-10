<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun($module, 'UpdateTradeinWeek', $_REQUEST);
} else {
  $id = sid($module, 'InsertTradeinWeek', $_REQUEST);
  if ($id > 0) {
    set_cookie('flash', 'New trade-in week is registered');
  } else {
    set_cookie('error', 'Trade-in week data could not be added');
  }
}
go("?p=$module/tradeins");

?>
