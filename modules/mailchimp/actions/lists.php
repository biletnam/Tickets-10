<?php

$pageParams = array();
use objMailChimp;
$objMCh = new objMailChimp($r);

$lists = $objMCh->lists(); 
$pageParams['lists'] = $lists;
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'lists.title', $pageParams);

$page->add('main', $runtime->txt->do_template($module, 'lists', $pageParams);



?>
