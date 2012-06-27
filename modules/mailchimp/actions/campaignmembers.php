<?php

use objMailChimp;
$objMCh = new objMailChimp($r);

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $campaigns = $objMCh->campaigns($id);
  $campaign = $campaigns[0]; 
  $members = $objMCh->campaign_members($id);
  $campaign['members'] = $members ; 
  $page->add('title',  $campaign['pagetitle'] = $r->txt->do_template($module, 'campaignmembers.title', $campaign);
  $page->add('main', $r->txt->do_template($module, 'campaignmembers', $campaign);
}



?>
