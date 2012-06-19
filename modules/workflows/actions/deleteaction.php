<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $actionInfo = $runtime->s2r($module, 'GetActionInfo', $_REQUEST);
  $workflow = $actionInfo['workflow'] || 0;
  $runtime->db->sqlrun($module, 'DeleteAction', $_REQUEST);
  go("?p=$module/edit&id=$workflow&tab=actions") if ($workflow > 0);
}

go("?p=$module/list");

?>
