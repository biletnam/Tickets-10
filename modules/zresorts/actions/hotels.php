<?php

$pageParams = array();
$pageParams['hotels'] = arr2ref(s2a($module, 'ListHotels'));
$pageParams['pagetitle'] = $page->add('title',  $runtime->doTemplate($module, 'hotels.title');
$page->add('main', $runtime->doTemplate($module, 'hotels', $pageParams);




?>
