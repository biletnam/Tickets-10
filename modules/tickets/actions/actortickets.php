<?php

$pageParams = array();
$id = lavnn('actor', $_REQUEST, 0);
if ($id > 0) {
    $employeeInfo = $runtime->s2r($module, 'GetEmployeeInfo', array('id' => $id));
    $pageParams['employeeInfo'] = $employeeInfo;
    $tickets = $runtime->s2a($module, 'ListActorTickets', array('actor' => $id));
    #print Dumper($tickets);
    $pageParams['tickets'] = $tickets;
    $runtime->saveMoment('  employee info fetched from db');
    if (count($employeeInfo) > 0) {
        $pagetitle = $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.actortickets', $employeeInfo);
        $page->add('title', $pagetitle);
        $page->add('main', $runtime->doTemplate($module, 'actortickets', $pageParams));
    }
}

?>
