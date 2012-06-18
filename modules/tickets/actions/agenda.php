<?php

$employeeInfo = $runtime->s2r($module, 'GetEmployeeInfo', $_REQUEST);
$runtime->saveMoment('  employee info fetched from db');
if (count($employeeInfo) > 0) {
  $agendatickets = $runtime->s2a($module, 'ListAgendaTickets', array('user_id' => $r['userInfo']['staff_id']));
  $employeeInfo['agendatickets'] = $agendatickets;
  $runtime->saveMoment('  list of tickets fetched from db');
  $page->add('main', $runtime->txt->do_template($module, 'agenda', $employeeInfo);
} else {
  $page->add('main', $runtime->txt->do_template($module, 'agenda.nouser');
}

$page->add('title',  $employeeInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.agenda');




?>
