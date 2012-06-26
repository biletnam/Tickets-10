<?php
$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $pageParams['id'] = $id;
  $prices = $runtime->s2a($module, 'ListHotelPrices', $_REQUEST);
  $pageParams['prices'] = $prices;
  print $runtime->txt->do_template($module, 'editprices.list', $pageParams);
}

?>
