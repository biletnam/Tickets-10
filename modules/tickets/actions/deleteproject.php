<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $projectInfo = $runtime->s2r($module, 'GetProjectInfo', $_REQUEST);
  if (count($projectInfo) > 0) {
    if ($projectInfo['creator'] <> $r['userInfo']['staff_id']) {
      $_SESSION['error'] = 'Only creator can delete the project');
    } elseif ($projectInfo['can_delete'] == '0') {
      $_SESSION['error'] = 'Only empty projects can be deleted');
    } else {
      $runtime->db->sqlrun($module, 'DeleteProject', $_REQUEST);
      $_SESSION['flash'] = "Project '".$projectInfo['title']."' deleted");
    }
  } else {
    $_SESSION['error'] = 'Requested project was not found');
  }
} else {
  $_SESSION['error'] = 'Choose project to delete');
}

go('?p=tickets/myprojects');

?>
