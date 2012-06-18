<?php
$eventInfo = $objCal->get_event_info(('id' => lavnn('id')));
if (count($eventInfo) > 0) {
  $dateTypeInfo = array(); $alert = '';
  $rowsaffected = srun($module, 'ChangeDayType', array('id' => lavnn('id'), 'day_type' => lavnn('type')));
  if ($rowsaffected > 0) {
    %dateTypeInfo = $runtime->s2r($module, 'GetAbsenceTypeInfo', array('code' => lavnn('type'))); 
    $alert = $runtime->doTemplate($module, 'alert.changetype');
  } else {
    %dateTypeInfo = $runtime->s2r($module, 'GetAbsenceTypeInfo', array('code' => $eventInfo['day_type']));
    $alert = $runtime->doTemplate($module, 'alert.typenotchanged');
  }
  print dot('daytype', $dateTypeInfo) . $alert;
} else {
  print dot('alert.typenotchanged');
}

1;
?>
