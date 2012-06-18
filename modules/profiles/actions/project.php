<?php

$id = lavnn('id');
if ($id > 0) {
  $projectInfo = $runtime->s2r($module, 'GetProjectDetails', $_REQUEST);
  #print spreview($module, 'GetProjectDetails', $_REQUEST) . Dumper($projectInfo);
  $runtime->saveMoment('  project info fetched from db');
  if (count($projectInfo) > 0) {
    $projectInfo['editable'] = $acc->check_access("editproject:$id");
    $projectInfo['editable'] ||= $projectInfo['creator'] == $r['userID'];
    $projectInfo['editable'] ||= $projectInfo['moderator'] == $r['userID'];
    $wallPosts = $objWall->get_posts((
      'entity_type' => 'project', 
      'entity_id' =>$id, 
      $projectInfo['editable']
    )); 
    #print Dumper($wallPosts);
    $projectInfo['wall'] = $objWall->render($wallPosts);
    $attachments = $runtime->s2a($module, 'ListAttachments', array(
      'entity_type' => 'project', 
      'entity_id' => $id
    ));
    $projectInfo['attachments'] = $attachments; 
    $page->add('title',  $projectInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.project', $projectInfo);
    $page->add('main', $runtime->txt->do_template($module, 'project', $projectInfo);
  } else {
    $page->add('title',  $projectInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.project.notfound');
    $page->add('main', $runtime->txt->do_template($module, 'project.notfound', $projectInfo);
  }
}



?>
