<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $history = $runtime->s2a($module, 'ListPhDocLocations', $_REQUEST);
  print dot('ajaxdochistory', array('locations' => $history)); 
}

1;

?>
