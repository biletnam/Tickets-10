<?php

$ids = lavnn('ids'); $cnt = 0;
foreach $id (split(',', $ids)) {
  $sqlParams = ('id' => $id);
  $dirty = lavnn("_dirty_$id");
  if ($dirty == '1') {
    $sqlParams['seqno'] = lavnn("seqno_$id");
    $sqlParams['code'] = lavnn("code_$id");
    $sqlParams['name'] = lavnn("name_$id");
    $sqlParams['image'] = lavnn("image_$id");
    srun($module, 'UpdateArticleBlock', $sqlParams);
    $cnt++;
  }
  if ($cnt > 0) {
    set_cookie('flash', "$cnt blocks updated");
  }
}
go("?p=$module/blocks");

?>
