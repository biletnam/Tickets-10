<?php

$pageParams = array();

$params = array('user_id' => $r['userID'], 'object_type' => 'employee', 'object_id' => $r['userID']);
$calendars = $objCal->list_calendars(%params); 
$pageParams['calendars'] = $calendars;

$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.mycalendars');
$page->add('main', $r->txt->do_template($module, 'mycalendars', $pageParams);



?>
