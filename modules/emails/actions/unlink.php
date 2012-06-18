<?php

$id = lavnn('id', $_REQUEST, 0);
use objEmployee;
$objEmp = new objEmployee($r);

if ($id <> '') {
  $objEmp->unlink_emp_email(('email' => $id));
}

go("?i=$module/promptlink&id=$id");

?>
