<?php
$id = lavnn('id', $_REQUEST, 0);
$weekInfo = array();
if ($id > 0) {
  %weekInfo = $runtime->s2r($module, 'GetTradeinWeekData', $_REQUEST); #print Dumper($weekInfo); 
  if ($weekInfo['external_resort'] <> '') {
    $extseasons = arr2ref(s2a($module, 'ListExtSeasons', array('resort' => $weekInfo['external_resort']))); 
    $extseasonoptions = arr2ref(genOptions($extseasons, 'id', 'name', $weekInfo['external_resort']));
    $weekInfo['preselectseason'] = $runtime->doTemplate($module, 'tradeintooltip.extseason', array('extseasons' => $extseasonoptions));
  }  
} else {
  $weekInfo['preselectseason'] = $runtime->doTemplate($module, 'tradeintooltip.extseason.wait');
}

$arrtypeoptions = arr2ref(s2a($module, 'ListAptTypes')); 
$weekInfo['apttypes'] = arr2ref(genOptions($arrtypeoptions, 'type_id', 'type_name', $weekInfo['apt_type']));
$extresorts = arr2ref(s2a($module, 'ListExtResorts')); 
$weekInfo['extresorts'] = arr2ref(genOptions($extresorts, 'id', 'name', $weekInfo['external_resort']));
$weekInfo['membershiptypes'] = arr2ref($runtime->getDictArr($module, 'extresort.membertype', $weekInfo['membership_type'])); 
print dot('tradeintooltip', $weekInfo);
?>
