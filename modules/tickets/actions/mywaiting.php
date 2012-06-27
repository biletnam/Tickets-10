<?php

$tickets = $r->s2a($module, 'ListActorTickets', array('actor' => $r['userID']));
$pageParams['tickets'] = $tickets;

$pagetitle = $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.mywaiting');
$page->add('title', $pagetitle);
$page->add('main', $r->txt->do_template($module, 'mywaiting', $pageParams));

?>