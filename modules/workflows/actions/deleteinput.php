<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $parameterInfo = $runtime->s2r($module, 'GetInputParameterInfo', $_REQUEST);
  $workflow = $parameterInfo['workflow'] || 0;
  srun($module, 'DeleteInputParameter', $_REQUEST);
  go("?p=$module/edit&id=$workflow&tab=inputs") if ($workflow > 0);
}

go("?p=$module/list");

?>
