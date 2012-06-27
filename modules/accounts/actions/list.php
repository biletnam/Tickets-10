<?php

/*

include 'objects/objAccounting.php';

$pageParams = array();
$objA = new objAccounting($r);

$pageParams['accounts'] = $objA->list();
$pageParams['pagetitle'] = $r->txt->do_template($module, 'title.list');
$page->add('title', $pageParams['pagetitle']);
*/
$page->add('main', $r->txt->do_template('accounts', 'list', $pageParams));

?>