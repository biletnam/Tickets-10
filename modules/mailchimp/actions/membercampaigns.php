<?php

$pageParams = array();

use objMailChimp;
$objMCh = new objMailChimp($r);

$email = lavnn('email', $_REQUEST, '');
if ($email <> '') {
  $campaigns = $objMCh->member_campaigns($email);
  $pageParams['campaigns'] = $campaigns;
  $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'membercampaigns.title', $_REQUEST);
  $page->add('main', $r->txt->do_template($module, 'membercampaigns', $pageParams);
}




?>
