<?php

$ticketInfo = ('creator' => $r['userInfo']['staff_id'], 'title' => lavnn('title'));
$target = $ticketInfo['target'] = lavnn('target', $_REQUEST, '');

$ticketing_type = $r['userInfo']['additionalData']['ticketing_type'] || '1';
if ($ticketing_type == '2' || $ticketing_type == '3') { 
  $adminmode = 'yes'; # For admins and moderators, show all projects
}

if ($target <> '') {
  $targets = array();
  foreach $t (split(',', $target)) {
    $target = $runtime->trim($t);
    $ti = ('target' => $target);
    $targetexplained = $objT->explain_target($target);
    $ti['targetexplained'] = $targetexplained['title'];
    # Also, check if there already are tickets for the same target
    $moretickets = $runtime->s2a($module, 'ListTargetTickets');
    if (count($moretickets) > 0) {
      $ti['moretickets'] = count($moretickets);
      $ti['moreticketslink'] = $runtime->doTemplate($module, 'new.target.more', $ticketInfo);
    }
    push @targets, $ti;
  }
  $ticketInfo['targets'] = loopt('new.target', @targets);
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

$page->add('title',  $ticketInfo['pagetitle'] = $runtime->doTemplate($module, 'title.new');
$page->add('main', $runtime->doTemplate($module, 'new', $ticketInfo);



?>
