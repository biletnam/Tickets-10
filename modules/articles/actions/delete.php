<?php
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $sqlParams = array('id' => $id, 'editor' => $r['userID']);
  $runtime->db->sqlrun($module, 'DeleteArticle', $sqlParams);
  $_SESSION['flash'] = 'Article deleted');
}
go("?p=$module/home");

?>
