<?php

$id = lavnn('id');
if ($id > 0) {
  $productInfo = $runtime->s2r($module, 'GetProductData', $_REQUEST); 

  use ctlTab;
  $tabProduct = new ctlTab($r, 'ctProduct');

  $tabProduct->addTab('edit', dot('view.tab.edit'), dot('view.edit', $productInfo));
  $obligations = $runtime->s2a($module, 'ListObligations', $productInfo);
  $productInfo['obligations'] = $obligations; 
  $tabProduct->addTab('obligations', dot('view.tab.obligations'), dot('view.obligations', $productInfo));  
  $tabProduct->setDefaultTab(lavnn('tab') || 'edit');
  $productInfo['tabcontrol'] = $tabProduct->getHTML();
  
  $page['js'] = dotmod('main', 'tabcontrol.js');
  $page['css'] = dotmod('main', 'tabcontrol.css');    

  $page->add('title',  $productInfo['pagetitle'] = $runtime->doTemplate($module, 'title.view', $productInfo);
  $page->add('main', $runtime->doTemplate($module, 'view', $productInfo);
}



  
?>
