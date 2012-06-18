<?php

$pageParams = array();

use objMailChimp;
$objMCh = new objMailChimp($r);

$email = lavnn('email', $_REQUEST, '');
if ($email <> '') {
  $lists = $objMCh->member_lists($email);
  $pageParams['lists'] = $lists;
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'memberlists.title', $_REQUEST);
  $page->add('main', $runtime->doTemplate($module, 'memberlists', $pageParams);
}




?>
