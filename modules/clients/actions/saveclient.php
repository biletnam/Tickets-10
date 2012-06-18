<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  # Save data in client table
#  formdebug(%_REQUEST); die spreview($module, 'UpdateClient', $_REQUEST);
  srun($module, 'UpdateClient', $_REQUEST);
  # Go back to editing page
  set_cookie('flash', 'Client data changed');
  go("?p=$module/viewclient&id=$id&tab=details");
} else {
  set_cookie('error', 'Failed to change client data.');
  go("?p=$module/search");
}
?>
