<?php

$products = $runtime->s2a($module, 'ListProducts', $_REQUEST);
$pageParams['products'] = $products;

$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.list', $pageParams);
$page->add('main', $runtime->txt->do_template($module, 'list', $pageParams);
 

?>
