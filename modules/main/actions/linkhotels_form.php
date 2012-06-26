<?php
$source = lavnn('src');
$controlname = lavnn('controlname');
if ($source <> '' && $controlname <> '') {
  $pageParams = array('src' => $source, 'controlname' => $controlname);
  $locations = $runtime->s2a($module, 'ListHotelLocations');
  $locationoptions = genOptions($locations, 'id', 'location_name');
  $pageParams['locationoptions'] = $locationoptions;
  print $runtime->txt->do_template($module, 'linkhotels.form', $pageParams);
}

1;

?>
