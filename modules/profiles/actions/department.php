<?php

$id = lavnn('id');
if ($id > 0) {
  $departmentInfo = $runtime->s2r($module, 'GetDepartmentDetails', $_REQUEST);
  #print Dumper($departmentInfo);
  $runtime->saveMoment('  department info fetched from db');
  if (count($departmentInfo) > 0) {
    $page->add('title',  $departmentInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.department', $departmentInfo);
    $wallPosts = $objWall->get_posts(('entity_type' => 'department', 'entity_id' =>$id, 'canedit' => $acc->can_edit_department($id)));
    $departmentInfo['wall'] = $objWall->render($wallPosts);
    $page->add('main', $runtime->txt->do_template($module, 'department', $departmentInfo);
  } else {
    $page->add('title',  $departmentInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.department.notfound');
    $page->add('main', $runtime->txt->do_template($module, 'department.notfound', $departmentInfo);
  }
}



?>
