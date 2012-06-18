<?php

use objStaffManagement;
$objSM = new objStaffManagement($r);

$user_id = lavnn('user_id', $_REQUEST, '');
$comment = lavnn('comment', $_REQUEST, '');
if ($user_id <> '' && $comment <> '') {
  $sqlParams = array('id' => $user_id, 'editor' => $r['userID'], 'comment' => $comment);
  $result = $objSM->add_comment(%sqlParams); 
  if ($result > 0) {
    set_cookie('flash', 'Comment added');
    go("?p=$module/employee&id=$user_id&tab=comments");  
  } else {
    set_cookie('error', 'Could not add comment');
    go("?p=$module/employee&id=$user_id");
  }
} else {
  set_cookie('error', 'Malformed request, showing list of offices instead');
  go("?p=$module/offices");
}


?>
