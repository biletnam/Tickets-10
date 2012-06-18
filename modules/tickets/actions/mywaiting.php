<?php

$tickets = $runtime->s2a($module, 'ListActorTickets', array('actor' => $r['userID']));
$pageParams['tickets'] = $tickets;

$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.mywaiting');
$page->add('main', $runtime->doTemplate($module, 'mywaiting', $pageParams);



?>
