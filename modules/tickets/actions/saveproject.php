<?php

$id = lavnn('id', $_REQUEST, '');
$_REQUEST['user_id'] = $r['userInfo']['staff_id'];
$_REQUEST['moderator'] = $_REQUEST['new_moderator'] if $_REQUEST['new_moderator'] <> ''; 
if ($id == '') {
  $id = sid($module, 'InsertProject', $_REQUEST);
  if ($id > 0) {
    set_cookie('flash', 'Project added');
    $_REQUEST['id'] = $id;
  } else {
    set_cookie('error', 'Could not add project');
  }
} else {
  srun($module, 'UpdateProject', $_REQUEST);
  set_cookie('flash', 'Project updated');
  if (lavnn('bReassign') <> '' && lavnn('moderator') <> '') {
    srun($module, 'ReassignProjectTickets', $_REQUEST);
  }
}

if ($id > 0) {
  go("?p=tickets/project&id=$id");
} else {
  go('?p=tickets/myprojects');
}

?>
