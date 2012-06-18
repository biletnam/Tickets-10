<?php

$ticketInfo = ('creator' => $r['userInfo']['staff_id']);
$target = $ticketInfo['target'] = lavnn('target', $_REQUEST, '');

$ticketing_type = $r['userInfo']['additionalData']['ticketing_type'] || '1';
if ($ticketing_type == '2' || $ticketing_type == '3') { 
  $adminmode = 'yes'; # For admins and moderators, show all projects
}

$targets = $objT->detect_targets(lavnn('url'));
$ticketInfo['targets'] = $runtime->doTemplate($module, 'new.include.targets', array('targets' => $targets)) if $targets <> ''; 

$projects = $objT->list_projects('read');
$project = lavnn('project');
if ($project <> '') {
  $projectoptions = genOptions($projects, 'id', 'title', $project);
  $ticketInfo['projectselect'] = $runtime->doTemplate($module, 'new.project.hardcoded', array('projects' => $projectoptions));
} else {
  $ticketInfo['projectselect'] = $runtime->doTemplate($module, 'new.project.select', array('projects' => $projects));
}

$priorityoptions = $runtime->getSortedDictArr($module, 'priority', 0);
$ticketInfo['priorities'] = $priorityoptions;

$popupParams = (
  'title' => 'New Ticket',
  'content' => dot('new', $ticketInfo),
);

print dotmod('main', 'popup', $popupParams);

?>
