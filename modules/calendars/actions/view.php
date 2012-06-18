<?php

use Calendar;

$id = lavnn('id', $_REQUEST, '');
$special = lavnn('special', $_REQUEST, '');
if ($special == 'employeeabsence') {
  $employee_id = lavnn('employee_id') || $r['userID'];
  $specialInfo = $runtime->s2r($module, 'GetCalendarsByObject', array('object_type' => 'employeeabsence', 'object_id' => $employee_id));
  if (count($specialInfo) == 0) {
    $page->add('main', $runtime->doTemplate($module, 'view.create.absence.calendar', array('id' => $employee_id));
  } else {
    $id = $specialInfo['id'];
    # register pageview
    srun('main', 'RegisterPageview', array('entity_type' => 'absencecalendar', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));
  }
} 
if ($id <> '') {
  # First of all, get calendar data
  $calendarInfo = $runtime->s2r($module, 'GetCalendarData', array('id' => $id)); 
  $runtime->saveMoment('  fetched calendar data from db');
  if (count($calendarInfo) > 0) {
    # check access to the calendar
    $access = 'none';
    if ($acc['superadmin'] == 1 || $calendarInfo['creator'] == ($r['userID'])) {
      $access = 'edit';
    } elseif ($acc->check_resource("editcalendar:$id", $r['userID'])) {
      $access = 'edit';
    } elseif ($acc->check_resource("viewcalendar:$id", $r['userID'])) {
      $access = 'read';
    }

    # Do additional checks depending on calendar type    
    if ($calendarInfo['object_type'] == 'employeeabsence' && $calendarInfo['object_id'] > 0) {
      $employeeInfo = $runtime->s2r($module, 'GetEmployeeDetails', array('id' => $calendarInfo['object_id']));
      # Check if logged user has "viewoffice" rights 
      if ($acc->can_view_staff($employeeInfo['lngWorkPlace'])) {
        $access = 'read';
      }      
      # Calendar would be editable for employee's manager
      if ($r['userID'] == $employeeInfo['line_manager']) { 
        $access = 'edit';
      }
      # Also check if currently logged user is HR staff for the office where works employee 
      if ($acc->can_edit_staff($employeeInfo['lngWorkPlace'])) {
        $access = 'edit';
      }
    } elseif ($calendarInfo['object_type'] == 'office' && $calendarInfo['object_id'] > 0) {
      # HR for this office can edit the calendar
      if ($acc->can_edit_staff($calendarInfo['object_id'])) {
        $access = 'edit';
      }
    }
    $runtime->saveMoment('  access checked with result: '.$access);
#    print '  access checked with result: '.$access;

    if ($access == 'none') {
      $page->add('main', $runtime->doTemplate($module, 'view.noaccess');
    } else {

      #TODO we need to put date parameters into HTML    
      $parsedToday = Calendar::parseDate(Calendar::getToday()); 
      $calendarInfo['year'] = lavnn('year') || $parsedToday['year'];
      $calendarInfo['month'] = lavnn('month') || $parsedToday['month'];
      $calendarInfo['day'] = lavnn('day') || $parsedToday['day'];
      
      # Explain object type/id
      $calendarInfo['object'] = $objCal->explain_object(('object_type' => $calendarInfo['object_type'], 'object_id' => $calendarInfo['object_id']));
#      print Dumper($calendarInfo);

      # Render the tab control
      use ctlTab;
      $tcCalendar = new ctlTab($r, 'tcCalendar');
      $tcCalendar->addTab('edit', dot('view.tab.edit', $articleInfo), dot('view.edit', $calendarInfo)) if $access == 'edit';
      $tcCalendar->addTab('year', dot('view.tab.year', $articleInfo), dot('view.wait.year', $calendarInfo));
      $tcCalendar->addTab('month', dot('view.tab.month', $articleInfo), dot('view.wait.month', $calendarInfo));
      $tcCalendar->addTab('cal', dot('view.tab.cal', $articleInfo), dot('view.wait.cal', $calendarInfo)) if $access == 'edit' || $access == 'read';
#      $tcCalendar->addTab('week', dot('view.tab.week', $articleInfo), dot('view.wait.week', $calendarInfo));
      $tcCalendar->addTab('readaccess', dot('view.tab.readaccess', $articleInfo), dot('view.readaccess', $calendarInfo)) if $access == 'edit';
      $tcCalendar->addTab('editaccess', dot('view.tab.editaccess', $articleInfo), dot('view.editaccess', $calendarInfo)) if $access == 'edit';
      $tcCalendar->setDefaultTab(lavnn('tab')) if (lavnn('tab') <> '');
      $calendarInfo['tabcontrol'] = $tcCalendar->getHTML();
      $runtime->saveMoment('  rendered tab control');
      # render the page
      $page->add('title',  $calendarInfo['pagetitle'] = $runtime->doTemplate($module, 'title.view', $calendarInfo);
      $page->add('main', $runtime->doTemplate($module, 'view', $calendarInfo);
    }
  } else {
    $page->add('title',  $calendarInfo['pagetitle'] = $runtime->doTemplate($module, 'title.view.notfound');
    $page->add('main', $runtime->doTemplate($module, 'view.notfound', $calendarInfo);
  }
  
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page['js'] .= dotmod('main', 'linkpeople.js');
  $page['js'] .= dotmod($module, 'calendar.js', array('id' => $id));
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  $page->add('css',  dotmod('main', 'linkpeople.css');
  $page->add('css',  dotmod($module, 'calendar.css');
  $page->add('css',  $runtime->doTemplate($module, 'css');
  $page['cssfiles'] = $runtime->link_cssfiles(qw(calendar));
}





?>
