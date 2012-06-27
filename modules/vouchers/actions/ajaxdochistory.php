<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $history = $runtime->s2a($module, 'ListPhDocLocations', $_REQUEST);
  print $r->txt->do_template($module, 'ajaxdochistory', array('locations' => $history)); 
}

1;

?>
