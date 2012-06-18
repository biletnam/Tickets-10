<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $_REQUEST['usertype'] ||= 'U';
  $pollInfo = $objP->get_info(%_REQUEST);
  $id = $objP->delete(%_REQUEST);
  if ($id > 0) {
    set_cookie('flash', "Poll '".$pollInfo['title']."' deleted");
  } else {
    set_cookie('error', "Poll '".$pollInfo['title']."' could not be deleted");
  }
} else {
  set_cookie('error', "No poll found to delete");
}

go("?p=$module/list&tab=editlist");

?>
