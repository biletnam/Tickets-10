<?php

use objStaffManagement;
$objSM = new objStaffManagement($r);

$user_id = lavnn('user_id', $_REQUEST, '');
$comment = lavnn('comment', $_REQUEST, '');
if ($user_id <> '' && $comment <> '') {
  $sqlParams = array('id' => $user_id, 'editor' => $r['userID'], 'comment' => $comment);
  $result = $objSM->add_comment(%sqlParams); 
  if ($result > 0) {
    $_SESSION['flash'] = 'Comment added');
    go("?p=$module/employee&id=$user_id&tab=comments");  
  } else {
    $_SESSION['error'] = 'Could not add comment');
    go("?p=$module/employee&id=$user_id");
  }
} else {
  $_SESSION['error'] = 'Malformed request, showing list of offices instead');
  go("?p=$module/offices");
}


?>
