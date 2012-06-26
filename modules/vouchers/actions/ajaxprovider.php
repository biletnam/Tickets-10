<?php

$type = lavnn('type', $_REQUEST, '');

if ($type == 'hotel') {
  $hoteloptions = genOptions(arr2ref(s2a($module, 'ListHotels')), 'hotel_id', 'hotel_name');
  print $runtime->txt->do_template($module, 'ajaxprovider.hotels', array('hoteloptions' => $hoteloptions));
} elseif ($type == 'other') {
  $provideroptions = genOptions(arr2ref(s2a($module, 'ListProviders')), 'id', 'name');
  print $runtime->txt->do_template($module, 'ajaxprovider.providers', array('provideroptions' => $provideroptions));
}

1;

?>
