<?php

$id = lavnn('id');
$product_id = lavnn('product');
$obligationInfo = array();
if ($id > 0) {
  %obligationInfo = $runtime->s2r($module, 'GetObligationData', $_REQUEST); 
  $product_id = $obligationInfo['product_id'];
}
if ($product_id > 0) {
  $productInfo = $runtime->s2r($module, 'GetProductData', array('id' => $product_id)); 
  Arrays::copy_fields($obligationInfo, $productInfo, qw(product_name type_of_contract resort_code));
  $obligationInfo['periodicoptions'] = arr2ref($runtime->getSortedDictArr('main', 'yesno', $obligationInfo['is_periodical'])); 
  $obligationInfo['isbreakableoptions'] = arr2ref($runtime->getSortedDictArr('main', 'yesno', $obligationInfo['is_breakable']));
  $obligationInfo['requiresmaintfeesoptions'] = arr2ref($runtime->getSortedDictArr('main', 'yesno', $obligationInfo['requires_maintfees']));
  $obligationInfo['highseasonoptions'] = arr2ref($runtime->getSortedDictArr('main', 'yesno', $obligationInfo['season']));
  $obligationInfo['priorityusageoptions'] = arr2ref($runtime->getSortedDictArr('main', 'yesno', $obligationInfo['is_prioritized']));
  $arrtypeoptions = arr2ref(s2a($module, 'ListAptTypes')); 
  $obligationInfo['apttypeoptions'] = arr2ref(genOptions($arrtypeoptions, 'type_id', 'type_name', $obligationInfo['apt_type_id']));
  if ($id > 0) {
    $page->add('title',  $obligationInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.editobligation', $obligationInfo);
  } else {
    $obligationInfo['product_id'] = $product_id; 
    $page->add('title',  $obligationInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.editobligation.new', $obligationInfo);
  } 
}

$page->add('main', $runtime->txt->do_template($module, 'editobligation', $obligationInfo);



  
?>
