<?php

$id = lavnn('id');
if ($id <> 0) {
  $officeInfo = $runtime->s2r($module, 'GetOfficeDetails', $_REQUEST);
  #print Dumper($officeInfo);
  $runtime->saveMoment('  office info fetched from db');
  if (count($officeInfo) > 0) {
    $page->add('title',  $officeInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.office', $officeInfo);
    # Show office wall
    $wallPosts = $objWall->get_posts(('entity_type' => 'office', 'entity_id' =>$id, 'can_edit' => $acc->can_edit_staff($id))); #print Dumper($wallPosts);
    $officeInfo['wall'] = $objWall->render($wallPosts);
    $page->add('main', $runtime->txt->do_template($module, 'office', $officeInfo);
  } else {
    $page->add('title',  $officeInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.office.notfound');
    $page->add('main', $runtime->txt->do_template($module, 'office.notfound', $officeInfo);
  }
}



?>
