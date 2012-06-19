<?php
$from = lavnn('from', $_REQUEST, 0);
$to = lavnn('to', $_REQUEST, 0);
if ($from > 0) {
  if ($to > 0) {
    use objTicketing;
    $objT = new objTicketing($r);
    $objT->move_user_tickets($from, $to);
    $_SESSION['flash'] = 'Tickets transferred to another employee');
  }
  go("?p=$module/employee&id=$from");
} else {
  go("?p=$M/offices")
}

?>
