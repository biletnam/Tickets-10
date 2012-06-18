<?php

$id = lavnn('id', $_REQUEST, '');
$projectInfo = array();
if ($id <> '') {
  %projectInfo = $runtime->s2r($module, 'GetProjectInfo', $_REQUEST);
}

if (count($projectInfo) > 0) {
  # Check access to the project
  $access = 'none';
  if ($acc['superadmin'] == 1 || $projectInfo['moderator'] == ($r['userID']) || $projectInfo['creator'] == ($r['userID'])) {
    $access = 'edit';
  } elseif ($acc->check_resource("editproject:$id", $r['userID'])) {
    $access = 'edit';
  } elseif ($acc->check_resource("projecttickets:$id", $r['userID'])) {
    $access = 'read';
  }
  $runtime->saveMoment('  access checked with result: '.$access);
  if ($access == 'none') {
    $page->add('title',  $runtime->txt->do_template($module, 'title.noaccess');
    $page->add('main', $runtime->txt->do_template($module, 'project.noaccess');
  } else {
    $tickets = $runtime->s2a($module, 'ListProjectTickets', array('id' => $projectInfo['id'], 'user_id' => $r['userInfo']['staff_id']));
    $projectInfo['tickets'] = $tickets;
    $tags = $runtime->s2a($module, 'ListProjectTags', $projectInfo); 
    $projectInfo['tags'] = $tags;
    if ($access == 'edit') {
      # Add controls to manage moderator
      $projectInfo['setmoderator'] = $runtime->txt->do_template($module, 'project.edit.changemoderator', $projectInfo);
      # Prepare tab control
      use ctlTab;
      $tcProject = new ctlTab($r, 'tcProject');
      $tcProject->addTab('view', dot('project.tab.view', $projectInfo), view_project($projectInfo));
      $tcProject->addTab('edit', dot('project.tab.edit', $projectInfo), dot('project.edit', $projectInfo));
      $tcProject->addTab('tags', dot('project.tab.tags', $projectInfo), dot('project.tags', $projectInfo));
      $tcProject->addTab('attachments', dot('project.tab.attachments', $projectInfo), dot('project.wait.attachments', $projectInfo));
      $tcProject->addTab('viewers', dot('project.tab.viewers', $projectInfo), dot('project.viewers', $projectInfo));
      $tcProject->addTab('editors', dot('project.tab.editors', $projectInfo), dot('project.editors', $projectInfo));
      $tcProject->addTab('ticketaccess', dot('project.tab.ticketaccess', $projectInfo), dot('project.ticketaccess', $projectInfo));
      $tcProject->setDefaultTab(lavnn('tab')) if (lavnn('tab') <> '');
      $projectInfo['tabcontrol'] = $tcProject->getHTML();
      $projectInfo['createticketlink'] = $runtime->txt->do_template($module, 'project.createticketlink', $projectInfo);
      $runtime->saveMoment('  rendered tab control');
      # render the page
      $page->add('title',  $runtime->txt->do_template($module, 'title.project', $projectInfo);
      $projectInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.project.short', $projectInfo);
      $page->add('main', $runtime->txt->do_template($module, 'project', $projectInfo);
    } elseif ($access == 'read') {
      $projectInfo['tabcontrol'] = view_project($projectInfo);
      # render the page
      $page->add('title',  $runtime->txt->do_template($module, 'title.project', $projectInfo);
      $projectInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.project.short', $projectInfo);
      $page->add('main', $runtime->txt->do_template($module, 'project', $projectInfo);
    }
  }
} else {
  $page->add('title',  $projectInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.editproject.create');
  $projectInfo['setmoderator'] = $runtime->txt->do_template($module, 'project.edit.setmoderator');
  $projectInfo['tabcontrol'] = $runtime->txt->do_template($module, 'project.edit', $projectInfo);
  $page->add('main', $runtime->txt->do_template($module, 'project', $projectInfo);
}


$page['js'] .= dotmod('main', 'tabcontrol.js');
$page['js'] .= dotmod('main', 'linkpeople.js');
$page['js'] .= dotmod('main', 'tagcloud.js');
$page->add('css',  dotmod('main', 'tabcontrol.css');
$page->add('css',  dotmod('main', 'linkpeople.css');





function view_project {
  ($_projectInfo, $_articletags)

  $projectInfo = $_projectInfo};
  
  return dot('project.view', $projectInfo);
}

?>
