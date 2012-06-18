<?php
$source = lavnn('src');
$controlname = lavnn('controlname');
if ($source <> '' && $controlname <> '') {
  $pageParams = ('src' => $source, 'controlname' => $controlname);
  $locations = $runtime->s2a($module, 'ListHotelLocations');
  $locationoptions = genOptions($locations, 'id', 'location_name');
  $pageParams['locationoptions'] = $locationoptions;
  print dot('linkhotels.form', $pageParams);
}

1;

?>
