<?php

$pageParams = array();
#print spreview($module, 'SearchGenerators', $_REQUEST);
$results = $runtime->s2a($module, 'SearchGenerators', $_REQUEST);
if (count($results) > 100) {
  print dot('search.toomuch');
} elseif (count($results) > 0) {
  $pageParams['generators'] = $results;
  print dot('search.list', $pageParams);
} else {
  print dot('search.none');
}

?>
