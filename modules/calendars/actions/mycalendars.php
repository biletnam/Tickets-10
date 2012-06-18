<?php

$pageParams = array();

$params = ('user_id' => $r['userID'], 'object_type' => 'employee', 'object_id' => $r['userID']);
$calendars = $objCal->list_calendars(%params); 
$pageParams['calendars'] = $calendars;

$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.mycalendars');
$page->add('main', $runtime->doTemplate($module, 'mycalendars', $pageParams);



?>
