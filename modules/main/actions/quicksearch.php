<?php

$popupParams = array(
  'title' => 'Quick Search',
  'content' => dot('quicksearch', $ticketInfo),
);

print $runtime->txt->do_template('main', 'popup', $popupParams);


?>
