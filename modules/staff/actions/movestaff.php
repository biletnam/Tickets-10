<?php

use objEmployee;

$from = lavnn('from', $_REQUEST, 0);
$to = lavnn('to', $_REQUEST, 0);
if ($from > 0) {
  if ($to > 0) {
    $objEmp = new objEmployee($r);
    if ($from <> '' && $to <> '') {
      $objEmp->change_line_manager($from, $to);
      $_SESSION['flash'] = 'Line manager changed');
    }
  }
  go("?p=$module/employee&id=$from");
} else {
  go("?p=$M/offices")
}

?>
