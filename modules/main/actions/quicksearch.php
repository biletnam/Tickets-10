<?php

$popupParams = array(
  'title' => 'Quick Search',
  'content' => dot('quicksearch', $ticketInfo),
);

print dotmod('main', 'popup', $popupParams);


?>
