<?php

$pageParams = $runtime->s2r($module, 'GetSubteamDetails', $_REQUEST);
$team = lavnn('team', $_REQUEST, 0);
if ($team > 0) {
  $people = $runtime->s2a($module, 'ListSubteamEmployees', $_REQUEST);
  $pageParams['people'] = $people;
  print dot('subteam', $pageParams);
} else {
  print 'Unknown team';
}

?>
