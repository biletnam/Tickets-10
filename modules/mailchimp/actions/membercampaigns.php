<?php

$pageParams = array();

use objMailChimp;
$objMCh = new objMailChimp($r);

$email = lavnn('email', $_REQUEST, '');
if ($email <> '') {
  $campaigns = $objMCh->member_campaigns($email);
  $pageParams['campaigns'] = $campaigns;
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'membercampaigns.title', $_REQUEST);
  $page->add('main', $runtime->doTemplate($module, 'membercampaigns', $pageParams);
}




?>
