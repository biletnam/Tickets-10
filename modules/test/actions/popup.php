<?php

$popupParams = array(
  'title' => 'Lorem ipsum',
  'content' => $r->txt->do_template('test', 'lorem.ipsum'),
);

print $r->txt->do_template('test', 'popup', $popupParams);

?>
