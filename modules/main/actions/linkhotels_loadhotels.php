<?php
$location = lavnn('location', $_REQUEST, '');
$controlname = lavnn('controlname', $_REQUEST, '');
if ($location <> '') {
  $hotels = $runtime->s2a($module, 'ListHotels', array('location' => $location));
  Arrays::add_array_column($hotels, 'controlname', $controlname);
  if (count($hotels) > 0) {
    print dot('linkhotels.hotels.list', array('controlname' => $controlname, 'hotels' => $hotels));
  }
}
1;
?>
