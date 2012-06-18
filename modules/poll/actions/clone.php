<?php

$id = $objP->clone(%_REQUEST);
if ($id <> 0) {
  $pollInfo = $objP->get_info(%_REQUEST);
  set_cookie('flash', "New poll is cloned using template '".$pollInfo['title']."', continue editing it below.");
  go("?p=$module/edit&id=$id&tab=edit");
} else {
  go("?p=$module/list&tab=editlist");
}

1;
?>
