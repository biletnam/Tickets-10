<?php

$pageParams = array();
$page['js'] .= $runtime->txt->do_template($module, 'employeeshiftcalendar.js', $pageParams);
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.employeeshiftcalendar', $pageParams);
$page->add('main', $runtime->txt->do_template($module, 'employeeshiftcalendar', $pageParams);



?>
