<?php

use objMailChimp;
$objMCh = new objMailChimp($r);

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $campaigns = $objMCh->campaigns($id);
  $campaign = %{$campaigns[0]}; 
  $content = $objMCh->campaign_content($id); 
  $campaign['textcontent'] = $content['text']; 
  $campaign['htmlcontent'] = $content['html']; 
  $page->add('title',  $campaign['pagetitle'] = $runtime->doTemplate($module, 'campaign.title', $campaign);
  $page->add('main', $runtime->doTemplate($module, 'campaign', $campaign);
}




?>
