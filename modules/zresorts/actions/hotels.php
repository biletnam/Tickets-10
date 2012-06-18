<?php

$pageParams = array();
$pageParams['hotels'] = arr2ref(s2a($module, 'ListHotels'));
$pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'hotels.title');
$page->add('main', $runtime->txt->do_template($module, 'hotels', $pageParams);




?>
