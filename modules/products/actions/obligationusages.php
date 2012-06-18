<?php

$id = lavnn('id');
if ($id > 0) {
  $obligationInfo = $runtime->s2r($module, 'GetObligationData', $_REQUEST);
  if (count($obligationInfo) > 0) {
    $page->add('title',  $obligationInfo['pagetitle'] = $runtime->doTemplate($module, 'title.obligationusages', $obligationInfo);
    $obligationInfo['contracts'] = arr2ref(s2a($module, 'ListObligationContracts', $_REQUEST));
    $page->add('main', $runtime->doTemplate($module, 'obligationusages', $obligationInfo);
  } else {
    $page->add('title',  $obligationInfo['pagetitle'] = $runtime->doTemplate($module, 'title.obligationusages.none', $obligationInfo);
    $page->add('main', $runtime->doTemplate($module, 'obligationusages.none');
  }

}



  
?>
