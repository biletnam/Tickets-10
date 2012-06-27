<?php
$department = lavnn('dep', $_REQUEST, 0);
$controlname = lavnn('controlname', $_REQUEST, '');
if ($department > 0) {
  $offices = $runtime->s2a($module, 'ListOffices', array('department' => $department));
  Arrays::add_array_column($offices, 'controlname', $controlname);
  if (count($offices) > 0) {
    print $r->txt->do_template($module, 'linkpeople.offices.list', array('controlname' => $controlname, 'offices' => $offices));
  }
}
1;

?>
