<?php

$source = lavnn('src', $_REQUEST, '');
$controlname = lavnn('controlname', $_REQUEST, '');
if ($source <> '' && $controlname <> '') {
  $pageParams = array('src' => $source, 'controlname' => $controlname);
  $offices = $runtime->s2a($module, 'ListOffices');
  $officeoptions = genOptions($offices, 'lngId', 'NameCounted');
  $pageParams['officeoptions'] = $officeoptions;
  $departments = $runtime->s2a($module, 'ListDepartments');
  $departmentoptions = genOptions($departments, 'team_id', 'NameCounted');
  $pageParams['departmentoptions'] = $departmentoptions;
  print $r->txt->do_template($module, 'linkpeople.form', $pageParams);
}

?>
