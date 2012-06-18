<?php

$id = lavnn('id');
if ($id > 0) {
  $employeeInfo = $runtime->s2r($module, 'GetEmployeeDetails', $_REQUEST);
  #print Dumper($employeeInfo);
  $runtime->saveMoment('  person info fetched from db');
  if (count($employeeInfo) == 0) {
    $page->add('title',  $employeeInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.employee.notfound');
    $page->add('main', $runtime->txt->do_template($module, 'employee.notfound', $employeeInfo);
  } elseif($employeeInfo['is_fired'] == 1) {
    $page->add('title',  $employeeInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.employee.fired');
    $page->add('main', $runtime->txt->do_template($module, 'employee.fired', $employeeInfo);
  } else {
    $page->add('title',  $employeeInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.employee', $employeeInfo);
    $wallPosts = $objWall->get_posts(('entity_type' => 'employee', 'entity_id' =>$id, 'can_edit' => $acc->can_edit_employee($id))); #print Dumper($wallPosts);
    $employeeInfo['wall'] = $objWall->render($wallPosts);
    $page->add('main', $runtime->txt->do_template($module, 'employee', $employeeInfo);
  }
}



?>
