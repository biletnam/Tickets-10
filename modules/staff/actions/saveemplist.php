<?php
$id = lavnn('id');
if ($id > 0) {
  srun($module, 'UpdateEmpList', $_REQUEST);
} else {  
  $id = sid($module, 'InsertEmpList', $_REQUEST);
}
if ($id > 0) {
  go("?p=$module/editlist&id=$id&tab=members");
} else {  
  go("?p=$module/lists");
}

?>
