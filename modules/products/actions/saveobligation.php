<?php

$id = lavnn('id', $_REQUEST, 0);
$product_id = lavnn('product_id', $_REQUEST, 0);

if ($product_id > 0) {
  $productInfo = $runtime->s2r($module, 'GetProductData', array('id' => $product_id)); 
  $_REQUEST['product_name'] = $productInfo['product_name'];
  if ($id > 0) { #update
    $runtime->db->sqlrun($module, 'UpdateProductObligation', $_REQUEST);
  } else { #insert
    $_REQUEST['product_id'] = $product_id;
    $id = sid($module, 'InsertProductObligation', $_REQUEST);  
  }
  go("?p=$module/view&id=$product_id&tab=obligations");
} else {
  $runtime->set_cookie('error', 'No product chosen');
  go("?p=$module/list")
}

?>
