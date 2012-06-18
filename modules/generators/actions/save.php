<?php

$id = lavnn('generator_id', $_REQUEST, 0);
if ($id > 0) {
  # join addresses if they are several of them
  if (ref($_REQUEST['emails[]']) == 'ARRAY') {
    $_REQUEST['email'] = join(';', @{$_REQUEST['emails[]']});
  } else {
    $_REQUEST['email'] = $_REQUEST['emails[]'];
  }
  # Save data in client table
  srun($module, 'UpdateGenerator', $_REQUEST);
  # Go back to editing page
  set_cookie('flash', 'Generator data changed');
  go("?p=$module/view&id=$id&tab=details");
} else {
  set_cookie('error', 'Failed to change generator data.');
  go("?p=$module/search");
}
?>
