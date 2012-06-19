<?php

$id = sid($module, 'InsertArticleBlock', $_REQUEST);
if ($id > 0) {
  $_SESSION['flash'] = 'Article block added');
} else {
  set_cookie('error', 'Failed to add article block!');
}
go("?p=$module/blocks");

?>
