<?php

$pageParams = array();

use objMailChimp;
$objMCh = new objMailChimp($r);

$email = lavnn('email', $_REQUEST, '');
if ($email <> '') {
  $lists = $objMCh->member_lists($email);
  $pageParams['lists'] = $lists;
  $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'memberlists.title', $_REQUEST);
  $page->add('main', $r->txt->do_template($module, 'memberlists', $pageParams);
}




?>
