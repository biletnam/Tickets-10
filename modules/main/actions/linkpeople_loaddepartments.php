<?php
$office = lavnn('office', $_REQUEST, '');
$controlname = lavnn('controlname', $_REQUEST, '');
if ($office <> '') {
  $departments = $runtime->s2a($module, 'ListDepartments', array('office' => $office));
  Arrays::add_array_column($departments, 'controlname', $controlname);
  if (count($departments) > 0) {
    print $runtime->txt->do_template($module, 'linkpeople.departments.list', array('controlname' => $controlname, 'departments' => $departments));
  }
}
1;
?>
