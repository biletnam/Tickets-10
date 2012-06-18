<?php


$pageParams = array();

$pageParams['campaigns'] = arr2ref(s2a($module, 'ListCampaigns'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.campaigns');
$page->add('main', $runtime->doTemplate($module, 'campaigns', $pageParams);





?>
