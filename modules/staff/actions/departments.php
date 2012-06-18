<?php

$pageParams = array();

@departments = $runtime->s2a($module, 'DepartmentStaffStatistics');
$pageParams['departments'] = $departments;

$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.departments');
$page->add('main', $runtime->doTemplate($module, 'departments.list', $pageParams);



?>
