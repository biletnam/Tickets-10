<?php

$pageParams = array();
$page['js'] .= $runtime->doTemplate($module, 'employeeshiftcalendar.js', $pageParams);
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.employeeshiftcalendar', $pageParams);
$page->add('main', $runtime->doTemplate($module, 'employeeshiftcalendar', $pageParams);



?>
