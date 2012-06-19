<?php

$id = lavnn('id', $_REQUEST, 0);

if ($id > 0) {
  $obligationInfo = $runtime->s2r($module, 'GetObligationData', $_REQUEST);
  if (count($obligationInfo) > 0) {
    $product_id = $obligationInfo['product_id'] || 0;
    $runtime->db->sqlrun($module, 'DeleteProductObligation', $_REQUEST);
    if ($product_id > 0) {
      go("?p=$module/view&id=$product_id&tab=obligations");
    } else {
      go("?p=$module/list")
    }
  } else {
    $runtime->set_cookie('error', 'No obligation chosen');
    go("?p=$module/list")
  } 
} else {
  $runtime->set_cookie('error', 'No obligation chosen');
  go("?p=$module/list")
}

?>
