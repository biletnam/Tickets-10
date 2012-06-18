<?php

use ctlTab;
$pageParams = array();

$projects = $objT->list_projects('both');
$pageParams['projects'] = $projects;
$runtime->saveMoment('  list of projects fetched from db');

# TODO $acc->check_field('can_create_projects') might be obsolete
$pageParams['createprojectlink'] = $runtime->doTemplate($module, 'projects.createlink') if $acc->is_superadmin() || $acc->check_field('can_create_projects');
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.myprojects', $pageParams);
$page->add('main', $runtime->doTemplate($module, 'myprojects.'.(count($projects) > 0 ? 'list' : 'none'), $pageParams);



?>