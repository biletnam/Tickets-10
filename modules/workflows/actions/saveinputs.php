<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  # Save changes to existing inputs
  for $input (lavnn('inputs')) {
    $params = array(
      'id' => $input, 
      'param_type' => lavnn("type_$input"), 
      'param_caption' => lavnn("caption_$input"), 
      'param_name' => lavnn("name_$input")
    );
    $runtime->db->sqlrun($module, 'UpdateInputParameter', $params);
  }
  
  # Also add new inputs if both name and type are provided\
  if (lavnn('new_type') <> '' && lavnn('new_name') <> '') {
    $params = array(
      'workflow' => $id, 
      'param_type' => lavnn('new_type'), 
      'param_caption' => lavnn('new_caption'),
      'param_name' => lavnn('new_name')
    );
    $input = sid($module, 'InsertInputParameter', $params);
  }
  
  go("?p=$module/edit&id=$id&tab=inputs");
}
go("?p=$module/list");
?>
