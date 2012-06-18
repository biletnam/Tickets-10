<?php

#print Dumper($_REQUEST);
use objEmployee;
$objEmp = new objEmployee($r);

$id = lavnn('id', $_REQUEST, 0);
$year = lavnn('year', $_REQUEST, 0);
$month = lavnn('month', $_REQUEST, 0);
if ($id > 0) {
  $calendarInfo = $runtime->s2r($module, 'GetCalendarData', $_REQUEST); 
  $object_type = $calendarInfo['object_type'] || '';
  $object_id = $calendarInfo['object_id'] || 0;
  $runtime->saveMoment('  fetched calendar data from db');

  $employeeInfo = array();
  
  # check access to the calendar
  $access = 'none';
  if ($acc['superadmin'] == 1 || $calendarInfo['creator'] == ($r['userID'])) {
    $access = 'edit';
  } elseif ($acc->check_resource("editcalendar:$id", $r['userID'], $user_office, $user_department)) {
    $access = 'edit';
  } elseif ($acc->check_resource("viewcalendar:$id", $r['userID'], $user_office, $user_department)) {
    $access = 'read';
  }

  # Do additional checks depending on calendar type    
  if ($calendarInfo['object_type'] == 'employeeabsence' && $calendarInfo['object_id'] > 0) {
    %employeeInfo = $runtime->s2r($module, 'GetEmployeeDetails', array('id' => $calendarInfo['object_id'])); 
    # Check if currently logged user is HR staff for the office where works employee 
    if ($acc->can_edit_staff($employeeInfo['lngWorkPlace'])) {
      $access = 'edit';
    } elseif ($acc->can_view_staff($employeeInfo['lngWorkPlace'])) {
      $access = 'read' if $access <> 'edit'; # Do not downgrade
    }
    # Calendar would be editable for employee's manager
    if ($r['userID'] == $employeeInfo['line_manager']) { 
      $access = 'edit';
    }
  } elseif ($calendarInfo['object_type'] == 'office' && $calendarInfo['object_id'] > 0) {
    # HR for this office can edit the calendar
    if ($acc->can_edit_staff($calendarInfo['object_id'])) {
      $access = 'edit';
    }
  }
  $runtime->saveMoment('  access checked with result: '.$access);

  if ($access <> 'none') {
    # Get list of events in this calendar
    $sqlParams = ('calendar' => $id, 'year' => lavnn('year'), 'month' => lavnn('month'));
    $office_events = array();
    $events = $objCal->list_events(%sqlParams);
    $runtime->saveMoment('  fetched list of calendar events from db');
    # Add more calendars if needed, depending on object_type
    if ($calendarInfo['object_type'] == 'employeeabsence' || $calendarInfo['object_type'] == 'employee') {
      @office_events = $objCal->list_employee_office_events(('employee' => $calendarInfo['object_id'], 'year' => lavnn('year'), 'month' => lavnn('month')));
    }
    #print Dumper($office_events);
  
    # prepare parameters for API call
    $weekend_days = qw(6 7);
    $apiparams = (
        'weekend_days' => $weekend_days,
        'month' => ($month || ''), 
        'year' => ($year || ''), 
        'yearfrom' => 2010, 
        'yearto' => 2020
    );
    # if office calendar is known, prepare %officedata
    if (count($office_events) > 0) {
      $apiparams['officedata'] = hash2ref(Arrays::slice_array($office_events, 'D'));
    }
    # prepare both data and custom data for this day
    $data = array(); $customdata = array();
    foreach $event (@events) {
      $day = $event['D'];
      $data{$day} .= $runtime->doTemplate($module, "month.event.$object_type", $$event);
    }
    $apiparams['data'] = $data;
    $apiparams['year'] = $_REQUEST['year']; 
    $apiparams['month'] = $_REQUEST['month']; 
    $apiparams['template-prevmonth'] = gett('calendar.prevmonth');
    $apiparams['template-nextmonth'] = gett('calendar.nextmonth');
    $apiparams['template-nodata'] = gett('month.template.nodata');
    $apiparams['template-selectable'] = gett('month.template.selectable');
    $apiparams['template-preselected'] = gett('month.template.preselected');
    $apiparams['template-empty'] = gett('month.template.empty');
    # call the API
    $result = $runtime->do_api('calendars/monthcalendar', $apiparams);
    $result['id'] = lavnn('id');
    # render results received from API to a markup 
    if ($object_type == 'employeeabsence') {
      $daytypes = $runtime->s2a($module, 'ListCalendarDayTypes', array('office' => $employeeInfo['lngWorkPlace']));
      $balanceInfo = $objEmp->get_vacation_balance(('id' => $object_id, 'year' => $year));
      $absencebalances = $objEmp->parse_balances(('absences' => $balanceInfo['absences']));
      $balanceInfo['absencebalances'] = $absencebalances;     
      $balanceInfo['day_type_options'] = arr2ref(genOptions($daytypes, 'code', 'name', 'V'));
      $balanceInfo['qty_options'] = arr2ref($runtime->getDictArr($module, 'absence.qty', '1.0'));
      $result['morefields'] = $runtime->doTemplate($module, 'view.month.edit.employeeabsence', $balanceInfo);
    } elseif ($object_type == 'office') {
      $opt = $runtime->getDictArr($module, 'office.day_type'); 
      $result['day_type_options'] = arr2ref($runtime->getDictArr($module, 'office.day_type', 'WE'));
      $result['mandatory_vacation_options'] = arr2ref($runtime->getDictArr('main', 'yesno', '0'));
      $result['transferable_options'] = arr2ref($runtime->getDictArr('main', 'yesno', '1'));
      $result['morefields'] = $runtime->doTemplate($module, 'view.month.edit.office', $result);
    }
    $result['edit'] = $runtime->doTemplate($module, 'view.month.edit', $result) if $access == 'edit';
    $result['object_type'] = $object_type;
    $result['object_id'] = $object_id;
    print dot('month', $result);
  }
}

1;
  
?>
