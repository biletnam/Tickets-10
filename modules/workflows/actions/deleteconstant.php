<?php
$id = lavnn('id');
if ($id > 0) {
  $constantDetails = $runtime->s2r($module, 'GetConstantInfo', $_REQUEST);
  if (count($constantDetails) > 0) {
    $workflow = $constantDetails['workflow'];
    if (srun($module, 'DeleteConstant', $_REQUEST) > 0) {
      set_cookie('flash', 'Constant deleted');    
    } else {
      set_cookie('error', 'Could not delete constant');    
    }
    go("?p=$module/edit&id=$workflow&tab=constants");
  } else {
    set_cookie('error', 'Could not find constant to delete');
  }
}
go("?p=$module/list");

?>
