<?php

$pageParams = array();
#print spreview($module, 'SearchClients', $_REQUEST);
$results = $runtime->s2a($module, 'SearchClients', $_REQUEST);
if (count($results) > 100) {
  print $runtime->txt->do_template($module, 'searchclients.toomuch');
} elseif (count($results) > 0) {
  $pageParams['clients'] = $results;
  print $runtime->txt->do_template($module, 'searchclients.list', $pageParams);
} else {
  print $runtime->txt->do_template($module, 'searchclients.none');
}

?>
