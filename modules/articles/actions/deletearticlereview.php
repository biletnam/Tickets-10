<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $statusinfo = $runtime->s2r('main', 'GetReviewStatus', array('id' => $id));
  $article = $statusinfo['resource_id'];
  srun('main', 'DeleteReviewStatus', $_REQUEST);
  set_cookie('flash', 'Your opinion is deleted. You might make your statement anew.');
  go("?p=$module/review&id=$article");
} else {
  go("?p=$module/home");
}
?>
