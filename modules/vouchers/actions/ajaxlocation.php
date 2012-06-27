<?php

$type = lavnn('type', $_REQUEST, '');
if ($type == 'hotel') {
  $locationoptions = genOptions(arr2ref(s2a($module, 'ListHotels')), 'hotel_id', 'hotel_name');
  print $r->txt->do_template($module, 'ajaxlocation', array('locationoptions' => $locationoptions)); 
} elseif ($type == 'provider') {
  $locationoptions = genOptions(arr2ref(s2a($module, 'ListProviders')), 'id', 'name');
  print $r->txt->do_template($module, 'ajaxlocation', array('locationoptions' => $locationoptions)); 
} elseif ($type == 'stock') {
  $locationoptions = genOptions(arr2ref(s2a($module, 'ListStock')), 'id', 'name');
  print $r->txt->do_template($module, 'ajaxlocation', array('locationoptions' => $locationoptions)); 
} elseif ($type == 'office') {
  $locationoptions = genOptions(arr2ref(s2a($module, 'ListOffices')), 'lngId', 'strName');
  print $r->txt->do_template($module, 'ajaxlocation', array('locationoptions' => $locationoptions)); 
} elseif ($type == 'employee') {
  print $r->txt->do_template($module, 'ajaxlocation.employee'); 
} 

1;

?>
