<?php

/*

include 'objects/objAccounting.php';

$pageParams = array();
$objA = new objAccounting($r);

$pageParams['accounts'] = $objA->list();
$pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.list');
$page->add('title', $pageParams['pagetitle']);
*/
$page->add('main', $runtime->doTemplate('accounts', 'list', $pageParams));

?>