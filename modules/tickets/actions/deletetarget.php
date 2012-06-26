<?php

$id = lavnn('id');
if ($id > 0) {
  $targetInfo = $runtime->s2r($module, 'GetTarget', array('id' => $id)); 
  if (count($targetInfo) > 0) {
    $ticket = $targetInfo['ticket'] || 0; 
    $result = $objT->delete_target($id);
    if ($result > 0) {
      $_SESSION['flash'] = 'Target deleted');
    } else {
      $_SESSION['error'] = 'Could not delete target');
    }
    go("?p=$module/viewticket&id=$ticket");
  } else {
    $_SESSION['error'] = 'Could not delete target');
  }
}
go('');

?>
