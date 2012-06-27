<?php

$pageParams = array();
$id = lavnn('actor', $_REQUEST, 0);
if ($id > 0) {
    $employeeInfo = $r->s2r($module, 'GetEmployeeInfo', array('id' => $id));
    $pageParams['employeeInfo'] = $employeeInfo;
    $tickets = $r->s2a($module, 'ListActorTickets', array('actor' => $id));
    #print Dumper($tickets);
    $pageParams['tickets'] = $tickets;
    $r->saveMoment('  employee info fetched from db');
    if (count($employeeInfo) > 0) {
        $pagetitle = $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.actortickets', $employeeInfo);
        $page->add('title', $pagetitle);
        $page->add('main', $r->txt->do_template($module, 'actortickets', $pageParams));
    }
}

?>
