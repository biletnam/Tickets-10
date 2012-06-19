<?php

$ids = lavnn('ids'); $cnt = 0;
foreach $id (split(',', $ids)) {
  $sqlParams = array('id' => $id);
  $dirty = lavnn("_dirty_$id");
  if ($dirty == '1') {
    $sqlParams['seqno'] = lavnn("seqno_$id");
    $sqlParams['code'] = lavnn("code_$id");
    $sqlParams['name'] = lavnn("name_$id");
    $sqlParams['image'] = lavnn("image_$id");
    $runtime->db->sqlrun($module, 'UpdateArticleBlock', $sqlParams);
    $cnt++;
  }
  if ($cnt > 0) {
    $_SESSION['flash'] = "$cnt blocks updated");
  }
}
go("?p=$module/blocks");

?>
