<?php
$id = lavnn('id', $_REQUEST, 0);
$campaignInfo = array();
if ($id > 0) {
  %campaignInfo = $runtime->s2r($module, 'GetCampaignInfo', $_REQUEST);
  $page->add('title',  $campaignInfo['pagetitle'] = $runtime->doTemplate($module, 'title.editcampaign', $campaignInfo);
  use ctlTab;
  $tabCampaign = new ctlTab($r, 'ctCampaign');
  $tabCampaign->addTab('details', dot('editcampaign.tab.details'), dot('editcampaign.details', $campaignInfo));
  $tabCampaign->addTab('hotels', dot('editcampaign.tab.hotels'), dot('editcampaign.wait.hotels', $campaignInfo));
  $page['js'] .= dotmod('main', 'linkhotels.js');
  $page->add('css',  dotmod('main', 'linkhotels.css');
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  $tabCampaign->setDefaultTab(lavnn('tab') || 'details');
  $campaignInfo['tabcontrol'] = $tabCampaign->getHTML();
} else {
  $page->add('title',  $campaignInfo['pagetitle'] = $runtime->doTemplate($module, 'title.editcampaign.new');
  $campaignInfo['tabcontrol'] = $runtime->doTemplate($module, 'editcampaign.details'); 
}
$page->add('main', $runtime->doTemplate($module, 'editcampaign', $campaignInfo);

?>
