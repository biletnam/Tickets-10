<?php

$type = lavnn('type', $_REQUEST, '');

if ($type == 'hotel') {
  $hoteloptions = genOptions(arr2ref(s2a($module, 'ListHotels')), 'hotel_id', 'hotel_name');
  print dot('ajaxprovider.hotels', array('hoteloptions' => $hoteloptions));
} elseif ($type == 'other') {
  $provideroptions = genOptions(arr2ref(s2a($module, 'ListProviders')), 'id', 'name');
  print dot('ajaxprovider.providers', array('provideroptions' => $provideroptions));
}

1;

?>
