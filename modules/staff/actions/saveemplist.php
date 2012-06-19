<?php
$id = lavnn('id');
if ($id > 0) {
  $runtime->db->sqlrun($module, 'UpdateEmpList', $_REQUEST);
} else {  
  $id = sid($module, 'InsertEmpList', $_REQUEST);
}
if ($id > 0) {
  go("?p=$module/editlist&id=$id&tab=members");
} else {  
  go("?p=$module/lists");
}

?>
