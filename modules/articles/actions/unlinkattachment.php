<?php

$article = lavnn('article', $_REQUEST, 0);
$fileid = lavnn('fileid', $_REQUEST, 0);
if ($article > 0) {
  if ($fileid > 0) {
    $runtime->db->sqlrun($module, 'UnlinkAttachment', array('article' => $article, 'fileid' => $fileid));
  } 
  $articleInfo = $runtime->s2r($module, 'GetArticleData', array('id' => $article));
  if (count($articleInfo) > 0) {
    go("?p=$module/edit&id=$article&tab=attachments");
  }
}
go('?p=$module/home');

?>
