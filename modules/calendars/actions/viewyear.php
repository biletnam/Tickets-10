<?php

use Calendar;
use objEmployee;
$objEmp = new objEmployee($r);

$id = lavnn('id', $_REQUEST, 0);
$year = lavnn('year', $_REQUEST, 0);
$office = 0;
if ($id > 0) {
  $calendarInfo = $runtime->s2r($module, 'GetCalendarData', $_REQUEST); 
  $runtime->saveMoment('  fetched calendar data from db');

  $user_office = $office = $r['userInfo']['lngWorkPlace'];
  $officeInfo = $runtime->s2r('staff', 'GetOfficeDetails', array('office' => $user_office));
  # check access to the calendar
  $access = 'none';
  if ($acc['superadmin'] == 1 || $calendarInfo['creator'] == ($r['userID'])) {
    $access = 'edit';
  } elseif($acc->check_resource("editcalendar:$id", $r['userID'])) {
    $access = 'edit';
  } elseif ($acc->check_resource("viewcalendar:$id", $r['userID'])) {
    $access = 'read';
  }

  # Do additional checks depending on calendar type    
  if ($calendarInfo['object_type'] == 'employeeabsence' && $calendarInfo['object_id'] > 0) {
    # Get employee info to see the access level
    $employeeInfo = $runtime->s2r($module, 'GetEmployeeDetails', array('id' => $calendarInfo['object_id'])); 
    # Employee himself can send absence request
    if ($r['userID'] == $employeeInfo['lngId']) { 
      $access = 'requestabsence';
    # Calendar would be editable for employee's manager
    } elseif ($r['userID'] == $employeeInfo['line_manager']) {
      if ($acc->can_edit_staff($employeeInfo['lngWorkPlace'])) { 
        $access = 'hrauthorizeabsence'; 
      } else {
        $access = 'authorizeabsence'; 
      }
    # Also check if currently logged user is HR staff for the office where works employee 
    } elseif ($acc->can_edit_staff($employeeInfo['lngWorkPlace'])) {
      if ($officeInfo['hr_authorize_absence'] == 1) {
        $access = 'hrauthorizeabsence';
      } else {
        $access = 'edit';
      }
    } elseif ($acc->can_view_staff($employeeInfo['lngWorkPlace'])) {
      $access = 'view';
    }
    if ($access <> 'none') {
      $access = 'employeeabsence.'.$access;
    }
  }
  if ($calendarInfo['object_type'] == 'office' && $calendarInfo['object_id'] <> 0) {
    $office = $calendarInfo['object_id'];
    $officeInfo = $runtime->s2r($module, 'GetOfficeDetails', array('office' => $calendarInfo['object_id']));
    if ($acc->can_edit_staff($calendarInfo['object_id']) && count($officeInfo) > 0) {
      $access = 'edit';
    }
    if ($access <> 'none') {
      $access = 'office.'.$access;
    }
  }
  $runtime->saveMoment('  access checked with result: '.$access);
  print "<!--$access-->";

  if ($access <> 'none') { 
    %pageParams = %_REQUEST;
    # Fill in Year select box
    $years = Calendar::getYears(2010, 2020, $year); 
    $pageParams['years'] = $years;
    # Get list of events in this calendar
    $sqlParams = array('calendar' => $id, 'year' => lavnn('year'));
    $events = $objCal->list_events(%sqlParams);
    $runtime->saveMoment('  fetched list of calendar events from db');
    $eventsByMonth = slice_array($events, 'MM');
    $pageParams['htmlevents'] = hloopt("year.listitem.$access", 'year.listitem.group', 'asc', $eventsByMonth);
    $runtime->saveMoment('  list of calendar events sliced by month and processed with hloopt');
    # lookup even more information for custom object types 
    if ($calendarInfo['object_type'] == 'employeeabsence' && $calendarInfo['object_id'] > 0) {
      $balanceInfo = $objEmp->get_vacation_balance(('id' => $calendarInfo['object_id'], 'year' => lavnn('year')));
      $absencedays = $objEmp->parse_balances(('absences' => $balanceInfo['absences']));
      $pageParams['vacationbalance'] = $balanceInfo['vacationbalance'];
      $pageParams['absences'] = loopt('year.list.employeeabsence.typebalance', @absencedays);    
    }

    print dot("year.list.$access", $pageParams);
  }
}

1;
  
?>
