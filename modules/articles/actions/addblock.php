<?php

$id = $runtime->sid($module, 'InsertArticleBlock', $_REQUEST);
if ($id > 0) {
  $_SESSION['flash'] = 'Article block added');
} else {
  $_SESSION['error'] = 'Failed to add article block!');
}
go("?p=$module/blocks");

?>
