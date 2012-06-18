<?php

$id = lavnn('id');
if ($id > 0) {
  $targetInfo = $runtime->s2r($module, 'GetTarget', array('id' => $id)); 
  if (count($targetInfo) > 0) {
    $ticket = $targetInfo['ticket'] || 0; 
    $result = $objT->delete_target($id);
    if ($result > 0) {
      set_cookie('flash', 'Target deleted');
    } else {
      set_cookie('error', 'Could not delete target');
    }
    go("?p=$module/viewticket&id=$ticket");
  } else {
    set_cookie('error', 'Could not delete target');
  }
}
go('');

?>
