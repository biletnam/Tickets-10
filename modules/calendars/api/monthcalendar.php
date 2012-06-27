<?php

use Calendar;

# API 'monthcalendar'
#
# prepares month view calendar components. 
#
# returns hash with the following content:
#   weeks - table rendered from month days using three templates passed in parameters
#   months - array of SELECT options for choosing month, with requested month preselected
#   years - array of SELECT options for choosing year, with requested year preselected
#   month, year - values used for selecting month and year, also define days in 'weeks'
#   (note: markup for every rendered day has subdivs named day_<:v:i>, daydata_<:v:i>,
#     as well as value 'weekend' that specifies day to be either saturday or sunday)
#
# inputs:
#   month, year - if we want to define any month without selecting any date
#   date - to specify which date to select on a month view
#   (note: if neither month/year nor date is specified, current date is used)
#   weekend_days 
#
#   templateSelectable - html template to use for rendering any non-selected day in a calendar
#   templatePreselected - html template to use for rendering a selected day in a calendar
#   templateEmpty - html template to use for rendering a day which is out of requested month
#
#   control_id - name of the textbox control of a caller form where to return selected date
#
#   yearfrom, yearto - parameters to limit number of years in a select options
#
#   data, customdata 
#

$calendarData = $apiparams['data'];
$officeCalendarData = $apiparams['officedata'];
$customCalendarData = $apiparams['customdata'];

$weekenddays = $apiparams['weekend_days'];
$calendarParams = $apiparams['params'];

$parsedDate = array();
if ($apiparams['month'] <> '' && $apiparams['year'] <> '') {
  $parsedDate['month'] = sprintf("%02d", $apiparams['month']);
  $parsedDate['year'] = $apiparams['year'];
  $parsedToday = Calendar::parseDate(Calendar::getToday());
  if ($parsedToday['month'] == $apiparams['month'] && $parsedToday['year'] == $apiparams['year']) {
    $parsedDate['day'] = $parsedToday['day'];
  } else {
    # explicitly prevent some date to be preselected if month is not current
    $parsedDate['day'] = ''; 
  }
} else {
  $date = $apiparams['date'] || Calendar::getToday();
  %parsedDate = Calendar::parseDate($date);
}

# Get templates - either passed in parameters or default specified for API
$templateNoData = $apiparams['template-nodata'] || $runtime->gettmod($module, 'api.monthcalendar.day.nocalendar');
$templateSelectable = $apiparams['template-selectable'] || $runtime->gettmod($module, 'api.monthcalendar.day.selectable');
$templatePreselected = $apiparams['template-preselected'] || $runtime->gettmod($module, 'api.monthcalendar.day.preselected');
$templateEmpty = $apiparams['template-empty'] || $runtime->gettmod($module, 'api.monthcalendar.day.empty');
$templatePrevMonth = $apiparams['template-prevmonth'] || $runtime->gettmod($module, 'api.monthcalendar.prevmonth');
$templateNextMonth = $apiparams['template-nextmonth'] || $runtime->gettmod($module, 'api.monthcalendar.nextmonth');

$apiresults['control_id'] = $apiparams['control_id'] || '';
$month = $apiresults['month'] = $parsedDate['month'];
$year = $apiresults['year'] = $parsedDate['year'];
# Prepare months
$months = $runtime->getSortedDictArr('main', 'month', $month); 
$apiresults['months'] = $months;
# prepare years
$year_start = array($apiparams['yearfrom'] || 1900);
$year_end = array($apiparams['yearto'] || 2020);
$years = Calendar::getYears($year_start, $year_end, $year); 
$apiresults['years'] = $years;
# prepare previous month link if needed
if (($year - $year_start) * 12 + $month > 1) {
  $linkparams = array('month' => $month - 1, 'year' => $year);
  if (0 == $month - 1) {
    %linkparams = array('month' => 12, 'year' => $year - 1);
  }
  $apiresults['prevmonth'] = $r->txt->do_text($templatePrevMonth, $linkparams);
}
# prepare next month link if needed
if (($year_end + 1 - $year) * 12 - $month > 0) {
  $linkparams = array('month' => $month + 1, 'year' => $year);
  if ($month == 12) {
    %linkparams = array('month' => 1, 'year' => $year + 1);
  }
  $apiresults['nextmonth'] = $r->txt->do_text($templateNextMonth, $linkparams);
}

# gather some useful statistics about this month
$days = $runtime->s2a('main', 'GetEpochMonth', $parsedDate);
$firstday = $@days[0];
$base = $firstday['DoW'];
$num_of_days = count($days);

#initialize looping over month days
$i = 1; 
$week = '';

# Mark weekends in header or forget about them if detailed office calendar is passed
if (count($officeData) > 0) {
  undef @weekenddays;
} else {
  for ($i = 1; $i <= 7; $i++) {
    $apiresults{"weekend_$i"} = array(in_array($i, $weekenddays) ? "weekend" : "");
  }
}
# blank days before 1st day
for ($i = 1; $i < $base; $i++) {
  $week .= $templateEmpty;
}
# normal month days
for ($i = 1; $i <= $num_of_days; $i++) {
  $dayParams = %calendarParams;
  $customData = $customCalendarData{$i];
  $officeData = $officeCalendarData{$i];
  while (($key, $value) = each %customData) {
    $dayParams{$key} = $value;
  }
  $dayParams['i'] = $i;
  $dayParams['month'] = $apiresults['month']; 
  $dayParams['year'] = $apiresults['year'];
  
  if (count($officeData) > 0) {
    $hash = $officeData[0];
    $dayParams['OfficeDayType'] = 'OfficeDayType_'.$hash['day_type'];
    if ($hash['day_type'] <> 'WE') {
      $dayParams['data'] .= $runtime->$r->txt->do_template($module, 'api.monthcalendar.day.offevent', $hash);
    }
  } else {
    $dayParams['weekend'] = array(in_array(1 + (($i + $base - 2) % 7), $weekenddays) ? "weekend" : "");
  }
  

  if (sprintf("%02d", $parsedDate['day']) == sprintf("%02d", $i)) {
    $dayParams['data'] .= $calendarData{$i}; 
    $templateText = $templatePreselected || $templateNoData;
  } elseif (!exists $calendarData{$i}) {
    $templateText = $templateNoData;
  } else {
    $dayParams['data'] .= $calendarData{$i}; 
    $templateText = $templateSelectable || $templateNoData;
  }

  $week .= $r->txt->do_text($templateText, $dayParams);  
  if (($i + $base - 1) % 7 == 0) {
    $apiresults['weeks'] .= "<tr>$week</tr>";
    $week = '';
  } 
}
# blank days after last day
if (($i + $base - 2) % 7 > 0) {
  for ($j = array(($i + $base - 2) % 7) + 1; $j <= 7; $j++) {
    $week .= $templateEmpty;
  }
}
$apiresults['weeks'] .= "<tr>$week</tr>";



?>
