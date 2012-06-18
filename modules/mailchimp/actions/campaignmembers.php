<?php

use objMailChimp;
$objMCh = new objMailChimp($r);

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $campaigns = $objMCh->campaigns($id);
  $campaign = %{$campaigns[0]}; 
  $members = $objMCh->campaign_members($id);
  $campaign['members'] = $members ; 
  $page->add('title',  $campaign['pagetitle'] = $runtime->doTemplate($module, 'campaignmembers.title', $campaign);
  $page->add('main', $runtime->doTemplate($module, 'campaignmembers', $campaign);
}



?>
