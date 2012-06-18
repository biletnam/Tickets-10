<?php

$tickets = $runtime->s2a($module, 'ListActorTickets', array('actor' => $r['userID']));
$pageParams['tickets'] = $tickets;

$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.mywaiting');
$page->add('main', $runtime->txt->do_template($module, 'mywaiting', $pageParams);



?>
