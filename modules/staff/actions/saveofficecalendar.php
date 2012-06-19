<?php

use objCalendar;
$objCal = new objCalendar($r);

$id = lavnn('id', $_REQUEST, 0);
$office_id = lavnn('office_id', $_REQUEST, 0);
if ($id > 0) {
  $objCal->update_office_calendar(%_REQUEST);
  $_SESSION['flash'] = 'Office calendar updated');
} elseif ($office_id <> 0) {
  $officeInfo = $runtime->s2r($module, 'GetOfficeDetails', array('office' => $office_id));
  $calendarParams = array(
    'creator' => $r['userID'], 
    'object_type' => 'office', 
    'object_id' => $office_id, 
    'title' => lavnn('name') . ' (office ' . $officeInfo['strName'] . ')', 
    'description' => 'Office calendar: days off and special days'
  );
  $calendar_id = $_REQUEST['calendar_id'] = $objCal->create_calendar(%calendarParams);
  if ($calendar_id > 0) {
    $id = $objCal->create_office_calendar(%_REQUEST);
    if ($id > 0) {
      $_SESSION['flash'] = 'Office calendar created');
    } else {
      set_cookie('error', 'Office calendar could not be created');
    }
  } else {
    set_cookie('error', 'Could not create calendar');
  }
}

# Also, add information about additional absence types into zOfficeCalendarDays
while (($request_key, $request_value) = each %_REQUEST) {
  my($prefix, $suffix) = split('_', $request_key);
  $sqlParams = array('officecalendar' => $id, 'day_type' => $suffix, 'days_cnt' => $request_value);
  if ($prefix == 'type' && $suffix <> '' && $id > 0) {
    $existing = $runtime->s2r($module, 'GetCalendarTypeDays', $sqlParams);
    if (count($existing) > 0) {
      if ($request_value == '' || $request_value == '0') {
        $runtime->db->sqlrun($module, 'DeleteCalendarTypeDays', $sqlParams);
      } elseif (is_numeric($request_value)) {
        $runtime->db->sqlrun($module, 'UpdateCalendarTypeDays', $sqlParams) if ($existing['days_cnt'] <> $request_value);
      }
    } elseif($request_value <> '' && $request_value > 0)  {
      $runtime->db->sqlrun($module, 'InsertCalendarTypeDays', $sqlParams);
    }
    push @ids, $suffix;
  }
}

if ($office_id <> 0) {
  go("?p=$module/office&office=$office_id&tab=calendars");
} else {
  go("?p=$module/offices");
}

?>
