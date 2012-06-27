<?php
$id = lavnn('id', $_REQUEST, 0);
$campaignInfo = array();
if ($id > 0) {
  %campaignInfo = $runtime->s2r($module, 'GetCampaignInfo', $_REQUEST);
  $page->add('title',  $campaignInfo['pagetitle'] = $r->txt->do_template($module, 'title.editcampaign', $campaignInfo);
  use ctlTab;
  $tabCampaign = new ctlTab($r, 'ctCampaign');
  $tabCampaign->addTab('details', dot('editcampaign.tab.details'), dot('editcampaign.details', $campaignInfo));
  $tabCampaign->addTab('hotels', dot('editcampaign.tab.hotels'), dot('editcampaign.wait.hotels', $campaignInfo));
  $page['js'] .= $r->txt->do_template('main', 'linkhotels.js');
  $page->add('css',  $r->txt->do_template('main', 'linkhotels.css');
  $page['js'] .= $r->txt->do_template('main', 'tabcontrol.js');
  $page->add('css',  $r->txt->do_template('main', 'tabcontrol.css');
  $tabCampaign->setDefaultTab(lavnn('tab') || 'details');
  $campaignInfo['tabcontrol'] = $tabCampaign->getHTML();
} else {
  $page->add('title',  $campaignInfo['pagetitle'] = $r->txt->do_template($module, 'title.editcampaign.new');
  $campaignInfo['tabcontrol'] = $r->txt->do_template($module, 'editcampaign.details'); 
}
$page->add('main', $r->txt->do_template($module, 'editcampaign', $campaignInfo);

?>
