<?php

$pageParams = array();

@departments = $runtime->s2a($module, 'DepartmentStaffStatistics');
$pageParams['departments'] = $departments;

$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.departments');
$page->add('main', $runtime->txt->do_template($module, 'departments.list', $pageParams);



?>
