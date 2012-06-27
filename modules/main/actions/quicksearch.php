<?php

$popupParams = array(
  'title' => 'Quick Search',
  'content' => dot('quicksearch', $ticketInfo),
);

print $r->txt->do_template('main', 'popup', $popupParams);


?>
