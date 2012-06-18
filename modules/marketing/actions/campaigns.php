<?php


$pageParams = array();

$pageParams['campaigns'] = arr2ref(s2a($module, 'ListCampaigns'));
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.campaigns');
$page->add('main', $runtime->txt->do_template($module, 'campaigns', $pageParams);





?>
