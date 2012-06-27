<?php

$pageParams = array('user_id' => $_REQUEST['user_id']);
$tickets2follow = $runtime->s2a($module, 'ListTickets2Follow', $_REQUEST);
if (count($tickets2follow) > 0) {
  $pageParams['tickets2follow'] = $tickets2follow;
  $priorityoptions = $runtime->getSortedDictArr($module, 'priority');
  $pageParams['ticketpriorities'] = $priorityoptions;
  print $r->txt->do_template($module, 'tickets2follow.list', $pageParams);
} else {
  print $r->txt->do_template($module, 'tickets2follow.none');
}

?>
