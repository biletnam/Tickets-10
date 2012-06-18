<?php

/*

include 'objects/objAccounting.php';

$pageParams = array();
$objA = new objAccounting($r);

$pageParams['accounts'] = $objA->list();
$pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.list');
$page->add('title', $pageParams['pagetitle']);
*/
$page->add('main', $runtime->txt->do_template('accounts', 'list', $pageParams));

?>