<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  go("?p=$module/editaction&id=$id");
} else {
  go("?p=$module/list");
}

?>
