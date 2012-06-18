<?php

$ticketInfo = array('creator' => $r['userInfo']['staff_id'], 'title' => lavnn('title'));
$target = $ticketInfo['target'] = lavnn('target', $_REQUEST, '');

$ticketing_type = $r['userInfo']['additionalData']['ticketing_type'] || '1';
if ($ticketing_type == '2' || $ticketing_type == '3') { 
  $adminmode = 'yes'; # For admins and moderators, show all projects
}

if ($target <> '') {
  $targets = array();
  foreach (split(',', $target) as $t) {
    $target = trim($t);
    $ti = array('target' => $target);
    $targetexplained = $objT->explain_target($target);
    $ti['targetexplained'] = $targetexplained['title'];
    # Also, check if there already are tickets for the same target
    $moretickets = $runtime->s2a($module, 'ListTargetTickets');
    if (count($moretickets) > 0) {
      $ti['moretickets'] = count($moretickets);
      $ti['moreticketslink'] = $runtime->txt->do_template($module, 'new.target.more', $ticketInfo);
    }
    $targets[] = $ti;
  }
  $ticketInfo['targets'] = $r->txt->loop_template($module, 'new.target', $targets);
}

$projects = $objT->list_projects('read');
$project = lavnn('project');
if ($project <> '') {
  $projectoptions = genOptions($projects, 'id', 'title', $project);
  $ticketInfo['projectselect'] = $runtime->txt->do_template($module, 'new.project.hardcoded', array('projects' => $projectoptions));
} else {
  $ticketInfo['projectselect'] = $runtime->txt->do_template($module, 'new.project.select', array('projects' => $projects));
}

$priorityoptions = $runtime->getSortedDictArr($module, 'priority', 0);
$ticketInfo['priorities'] = $priorityoptions;
$ticketInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.new');
$page->add('title', $ticketInfo['pagetitle']);
$page->add('main', $runtime->txt->do_template($module, 'new', $ticketInfo));

?>
