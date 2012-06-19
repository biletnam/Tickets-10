<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $projectInfo = $runtime->s2r($module, 'GetProjectInfo', $_REQUEST);
  if (count($projectInfo) > 0) {
    if ($projectInfo['creator'] <> $r['userInfo']['staff_id']) {
      set_cookie('error', 'Only creator can delete the project');
    } elseif ($projectInfo['can_delete'] == '0') {
      set_cookie('error', 'Only empty projects can be deleted');
    } else {
      $runtime->db->sqlrun($module, 'DeleteProject', $_REQUEST);
      $_SESSION['flash'] = "Project '".$projectInfo['title']."' deleted");
    }
  } else {
    set_cookie('error', 'Requested project was not found');
  }
} else {
  set_cookie('error', 'Choose project to delete');
}

go('?p=tickets/myprojects');

?>
