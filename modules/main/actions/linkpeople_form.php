<?php

$source = lavnn('src');
$controlname = lavnn('controlname');
if ($source <> '' && $controlname <> '') {
  $pageParams = array('src' => $source, 'controlname' => $controlname);
  $offices = $runtime->s2a($module, 'ListOffices');
  $officeoptions = genOptions($offices, 'lngId', 'NameCounted');
  $pageParams['officeoptions'] = $officeoptions;
  $departments = $runtime->s2a($module, 'ListDepartments');
  $departmentoptions = genOptions($departments, 'team_id', 'NameCounted');
  $pageParams['departmentoptions'] = $departmentoptions;
  print dot('linkpeople.form', $pageParams);
}

1;

?>
