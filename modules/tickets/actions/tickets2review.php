<?php

$pageParams = ('user_id' => $_REQUEST['user_id']);
$tickets2review = $runtime->s2a($module, 'ListTickets2Review', $_REQUEST);
if (count($tickets2review) > 0) {
  $pageParams['tickets2review'] = $tickets2review;
  $priorityoptions = $runtime->getSortedDictArr($module, 'priority');
  $pageParams['ticketpriorities'] = $priorityoptions;
  print dot('tickets2review.list', $pageParams);
} else {
  print dot('tickets2review.none');
}

?>
