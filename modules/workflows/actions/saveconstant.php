<?php

$id = lavnn('id');
if ($id > 0) {
  $cname = lavnn('const_name');
  $ctype = lavnn('const_type');
  $cvalue = lavnn('const_value');
  if ($cname == '' || $ctype == '' || $cvalue == '') {
    $_SESSION['error'] = 'Name, Type and Value should be all defined for a constant. Try again');
  } else {
    $newid = $runtime->sid($module, 'InsertConstant', array('workflow' => $id, 'ctype' => $ctype, 'cname' => $cname, 'cvalue' => $cvalue));
    if ($newid > 0) {
      $_SESSION['flash'] = 'Constant added');
    } else {
      $_SESSION['error'] = 'Could not add constant');
    }
  }
  go("?p=$module/edit&id=$id&tab=constants");
} else {
  go("?p=$module/list");
}

?>
