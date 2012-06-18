<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  # Save changes to existing inputs
  foreach $action (lavnn('actions')) {
    $params = array('id' => $action, 'action_seqno' => lavnn("seqno_$action"), 'action_name' => lavnn("name_$action"));
    srun($module, 'UpdateAction', $params);
  }
  
  # Also add new inputs if both name and type are provided\
  if (lavnn('new_type') <> '' && lavnn('new_name') <> '') {
    $params = array('workflow' => $id, 'action_type' => lavnn('new_type'), 'action_name' => lavnn('new_name'));
    $input = sid($module, 'InsertAction', $params);
  }
  
  go("?p=$module/edit&id=$id&tab=actions");
}
go("?p=$module/list");
?>
