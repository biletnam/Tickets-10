<?php
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $sqlParams = ('id' => $id, 'editor' => $r['userID']);
  srun($module, 'DeleteArticle', $sqlParams);
  set_cookie('flash', 'Article deleted');
}
go("?p=$module/home");

?>