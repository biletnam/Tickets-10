<?php

$pageParams = array();
$pageParams['hotels'] = arr2ref(s2a($module, 'ListHotels'));
$pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'hotels.title');
$page->add('main', $r->txt->do_template($module, 'hotels', $pageParams);




?>
