<?php

$inputname = lavnn('inputname', $_REQUEST, '');
if ($inputname <> '') {
  print $runtime->txt->do_template($module, 'runreport.params.employee.input', array('name' => $inputname));
}

1;
?>
