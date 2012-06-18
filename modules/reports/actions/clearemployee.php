<?php

$inputname = lavnn('inputname', $_REQUEST, '');
if ($inputname <> '') {
  print dot('runreport.params.employee.input', array('name' => $inputname));
}

1;
?>
