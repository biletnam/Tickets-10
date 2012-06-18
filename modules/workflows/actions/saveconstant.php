<?php

$id = lavnn('id');
if ($id > 0) {
  $cname = lavnn('const_name');
  $ctype = lavnn('const_type');
  $cvalue = lavnn('const_value');
  if ($cname == '' || $ctype == '' || $cvalue == '') {
    set_cookie('error', 'Name, Type and Value should be all defined for a constant. Try again');
  } else {
    $newid = sid($module, 'InsertConstant', array('workflow' => $id, 'ctype' => $ctype, 'cname' => $cname, 'cvalue' => $cvalue));
    if ($newid > 0) {
      set_cookie('flash', 'Constant added');
    } else {
      set_cookie('error', 'Could not add constant');
    }
  }
  go("?p=$module/edit&id=$id&tab=constants");
} else {
  go("?p=$module/list");
}

?>
