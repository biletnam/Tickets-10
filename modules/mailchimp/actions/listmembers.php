<?php

$pageParams = array();

use objMailChimp;
$objMCh = new objMailChimp($r);

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $objMCh->list_data($id);
  $members = $objMCh->list_members($id, 'subscribed');
  $pageParams['members'] = $members;
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'listmembers.title', $pageParams);
  $page->add('main', $runtime->doTemplate($module, 'listmembers', $pageParams);
}




?>
