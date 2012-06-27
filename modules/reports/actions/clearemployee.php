<?php

$inputname = lavnn('inputname', $_REQUEST, '');
if ($inputname <> '') {
  print $r->txt->do_template($module, 'runreport.params.employee.input', array('name' => $inputname));
}

1;
?>
