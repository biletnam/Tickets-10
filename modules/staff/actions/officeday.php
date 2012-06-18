<?php

$pageParams = %_REQUEST;

$calendaritems = $runtime->s2a($module, 'GetOfficeCalendarDay', $_REQUEST);

$weekends = array(); $specialdays = array(); $absences = array();
($i_weekend, $i_specialday, $i_absence);
foreach $calendaritem (@calendaritems) {
  if ($calendaritem['weekend'] == $calendaritem['eD']) {
    $i = count($weekends) + 1;
    $calendaritem['_i_'] = $i; $calendaritem['_mod2_'] = ($i % 2);
    push @weekends, $calendaritem;
  } elseif (($calendaritem['specialday'] || '') <> '') {
    $i = count($specialdays) + 1;
    $calendaritem['_i_'] = $i; $calendaritem['_mod2_'] = ($i % 2);
    push @specialdays, $calendaritem;
  } elseif (($calendaritem['absence_id'] || '') <> '') {
    $i = count($absences) + 1;
    $calendaritem['_i_'] = $i; $calendaritem['_mod2_'] = ($i % 2);
    push @absences, $calendaritem;
  }
}

if (count($weekends) > 0) {
  $pageParams['weekends'] = $runtime->doTemplate($module, 'officeday.weekends', array('count' => count($weekends), 'weekends' => $weekends));
}
if (count($specialdays) > 0) {
  $pageParams['specialdays'] = $runtime->doTemplate($module, 'officeday.specialdays', array('count' => count($specialdays), 'specialdays' => $specialdays));
}
if (count($absences) > 0) {
  $pageParams['absences'] = $runtime->doTemplate($module, 'officeday.absences', array('count' => count($absences), 'absences' =>$absences));
}

$page->add('main', $runtime->doTemplate($module, 'officeday', $pageParams);

?>
