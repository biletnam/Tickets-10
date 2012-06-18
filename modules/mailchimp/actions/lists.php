<?php

$pageParams = array();
use objMailChimp;
$objMCh = new objMailChimp($r);

$lists = $objMCh->lists(); 
$pageParams['lists'] = $lists;
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'lists.title', $pageParams);

$page->add('main', $runtime->doTemplate($module, 'lists', $pageParams);



?>
