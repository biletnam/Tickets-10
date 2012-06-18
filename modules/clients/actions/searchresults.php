<?php

$pageParams = array();
#print spreview($module, 'SearchClients', $_REQUEST);
$results = $runtime->s2a($module, 'SearchClients', $_REQUEST);
if (count($results) > 100) {
  print dot('searchclients.toomuch');
} elseif (count($results) > 0) {
  $pageParams['clients'] = $results;
  print dot('searchclients.list', $pageParams);
} else {
  print dot('searchclients.none');
}

?>
