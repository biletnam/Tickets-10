<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $workflowDetails = $objW->get_workflow($_REQUEST);
  if (count($workflowDetails) > 0) {
    if ($objW->delete_workflow($_REQUEST) == 0) {
      $_SESSION['error'] = 'Could not delete workflow');
    } else {
      $_SESSION['flash'] = 'Workflow deleted');
    }
  } else {
    $_SESSION['error'] = 'Please specify valid workflow to delete!');
  }
} else {
  $_SESSION['error'] = 'Please specify valid workflow to delete!');
}

go("?p=$module/list");
?>
