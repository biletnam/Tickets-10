<?php

$ticketInfo = ('creator' => $r['userInfo']['staff_id']);
$target = $ticketInfo['target'] = lavnn('target', $_REQUEST, '');

$ticketing_type = $r['userInfo']['additionalData']['ticketing_type'] || '1';
if ($ticketing_type == '2' || $ticketing_type == '3') { 
  $adminmode = 'yes'; # For admins and moderators, show all projects
}

if ($target <> '') {
  $targetexplained = $objT->explain_target($target);
  $ticketInfo['targetexplained'] = $targetexplained['title'];
  # Also, check if there already are tickets for the same target
  $moretickets = $runtime->s2a($module, 'ListTargetTickets');
  if (count($moretickets) > 0) {
    $ticketInfo['moretickets'] = count($moretickets);
    $ticketInfo['moreticketslink'] = $runtime->doTemplate($module, 'new.target.more', $ticketInfo);
  }
}

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

print dot('new', $ticketInfo);

?>
