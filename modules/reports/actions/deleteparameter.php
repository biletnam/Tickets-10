<?php

$id = lavnn('id', $_REQUEST, 0);
$report = 0;
if ($id > 0) {
  $paramInfo = $runtime->s2r($module, 'GetParameterData', $_REQUEST);
  if (count($paramInfo) > 0) {
    $report = $paramInfo['report'];
    if (0 < $runtime->db->sqlrun($module, 'DeleteParameter', $_REQUEST)) {
      $_SESSION['flash'] = 'Parameter deleted');
    } else {
      set_cookie('error', 'Parameter not deleted');
    }
  }
}

if ($report > 0) {
  go("?p=reports/editreport&id=$report&tab=params");
}
go("?p=reports/editgallery");

?>
