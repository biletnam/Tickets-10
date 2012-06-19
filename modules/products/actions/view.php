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
  
  $page['js'] = $runtime->txt->do_template('main', 'tabcontrol.js');
  $page['css'] = $runtime->txt->do_template('main', 'tabcontrol.css');    

  $page->add('title',  $productInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.view', $productInfo);
  $page->add('main', $runtime->txt->do_template($module, 'view', $productInfo);
}



  
?>
