<?php

$pageParams = array();
#print spreview($module, 'SearchGenerators', $_REQUEST);
$results = $runtime->s2a($module, 'SearchGenerators', $_REQUEST);
if (count($results) > 100) {
  print $r->txt->do_template($module, 'search.toomuch');
} elseif (count($results) > 0) {
  $pageParams['generators'] = $results;
  print $r->txt->do_template($module, 'search.list', $pageParams);
} else {
  print $r->txt->do_template($module, 'search.none');
}

?>
