<?php

$id = $objP->create(%_REQUEST);
if ($id > 0) {
  go("?p=$module/edit&id=$id&tab=questions");
} else {
  go("?p=$module/list&tab=editlist");
}

?>
