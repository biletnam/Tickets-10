<?php

$popupParams = (
  'title' => 'Lorem ipsum',
  'content' => dotmod('test', 'lorem.ipsum'),
);

print dotmod('test', 'popup', $popupParams);

?>
