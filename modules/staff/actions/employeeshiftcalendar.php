<?php

$pageParams = array();
$page['js'] .= $r->txt->do_template($module, 'employeeshiftcalendar.js', $pageParams);
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.employeeshiftcalendar', $pageParams);
$page->add('main', $r->txt->do_template($module, 'employeeshiftcalendar', $pageParams);



?>
