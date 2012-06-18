<?php

$type = lavnn('type', $_REQUEST, '');
if ($type == 'polltemplate') {
  use objPoll;
  $objP = new objPoll($r);
  $polltemplates = $objP->list_templates();
  $options = genOptions($polltemplates, 'id', 'title');
  print dot('browseconstantvalues.polltemplate', array('templates' => $options));
}

1;
?>
