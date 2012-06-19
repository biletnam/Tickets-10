<?php
$id = lavnn('id', $_REQUEST, 0);
$campaignInfo = array();
if ($id > 0) {
  %campaignInfo = $runtime->s2r($module, 'GetCampaignInfo', $_REQUEST);
  $page->add('title',  $campaignInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.editcampaign', $campaignInfo);
  use ctlTab;
  $tabCampaign = new ctlTab($r, 'ctCampaign');
  $tabCampaign->addTab('details', dot('editcampaign.tab.details'), dot('editcampaign.details', $campaignInfo));
  $tabCampaign->addTab('hotels', dot('editcampaign.tab.hotels'), dot('editcampaign.wait.hotels', $campaignInfo));
  $page['js'] .= $runtime->txt->do_template('main', 'linkhotels.js');
  $page->add('css',  $runtime->txt->do_template('main', 'linkhotels.css');
  $page['js'] .= $runtime->txt->do_template('main', 'tabcontrol.js');
  $page->add('css',  $runtime->txt->do_template('main', 'tabcontrol.css');
  $tabCampaign->setDefaultTab(lavnn('tab') || 'details');
  $campaignInfo['tabcontrol'] = $tabCampaign->getHTML();
} else {
  $page->add('title',  $campaignInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.editcampaign.new');
  $campaignInfo['tabcontrol'] = $runtime->txt->do_template($module, 'editcampaign.details'); 
}
$page->add('main', $runtime->txt->do_template($module, 'editcampaign', $campaignInfo);

?>
