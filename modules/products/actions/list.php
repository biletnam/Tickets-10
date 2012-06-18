<?php

$products = $runtime->s2a($module, 'ListProducts', $_REQUEST);
$pageParams['products'] = $products;

$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.list', $pageParams);
$page->add('main', $runtime->doTemplate($module, 'list', $pageParams);
 

?>
