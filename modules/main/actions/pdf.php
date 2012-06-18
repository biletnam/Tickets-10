<?php
$page = array(
  'title' => 'The Test Title',
  'main' => 'MAIN. TODO. This is some text to be written',
);
$html = dotmod('main', 'index.pdf', $page);
$r['fs']pdf($html, 'test.pdf');

?>
