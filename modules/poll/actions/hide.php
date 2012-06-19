<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $_REQUEST['usertype'] ||= 'U';
  $pollInfo = $objP->get_info(%_REQUEST);
  $id = $objP->hide(%_REQUEST);
  if ($id > 0) {
    $_SESSION['flash'] = "Poll '".$pollInfo['title']."' is now hidden");
  }
}

go("?p=$module/list&tab=editlist");

?>
