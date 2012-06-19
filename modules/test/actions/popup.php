<?php

$popupParams = array(
  'title' => 'Lorem ipsum',
  'content' => $runtime->txt->do_template('test', 'lorem.ipsum'),
);

print $runtime->txt->do_template('test', 'popup', $popupParams);

?>
