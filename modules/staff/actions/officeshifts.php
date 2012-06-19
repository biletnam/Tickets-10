<?php

use Calendar;
use objStaffManagement;
$objSM = new objStaffManagement($r);
 
$pageParams = array();
$office = lavnn('office') || $r['userInfo']['lngWorkPlace'];
if ($office <> 0) {
  %pageParams = $runtime->s2r($module, 'GetOfficeDetails', array('office' => $office)); 
  if (count($pageParams) > 0) {
    $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.officeshifts', $pageParams);
    # Get year and month from request, or set current by default
    $today = Calendar::getTodayArr();
    $Y = lavnn('Y') || $today['Y'];
    $M = lavnn('M') || $today['M'];
    # If previous or next month was requested, make the correction
    if (lavnn('move') == 'previous') { $M--; if ($M < 1) { $M = 12; $Y--; } }
    if (lavnn('move') == 'next') { $M++; if ($M > 12) { $M = 1; $Y++; } }
    # Set modified year and month to page
    $pageParams['Y'] = $Y;
    $pageParams['M'] = $M;
    # Get all days for this month
    $days = $runtime->s2a($module, 'ListEpochDays', array('Y' => $Y, 'M' => $M));
    $pageParams['days'] = $days;
    # List all shifts in the office, group them by employee
    $shifts = $runtime->s2a($module, 'ListOfficeShifts', array('office' => $office, 'Y' => $Y, 'M' => $M));
    $pageParams['shifts'] = $shifts;
    $empshifts = slice_array($shifts, 'employee');
    $staff = $runtime->s2a($module, 'ListOfficeShiftStaff', array('office' => $office));
    $ee = slice_array($staff, 'DepartmentName');
    print count($staff) . ' employees in ' . count($ee) . ' departments; ';
    $emptyrow = loopt('officeshifts.employee.emptyday', @days);
    $rows = array(); $birthdays = array();
    $firstDay = sprintf("%04s-%02s-01 00:00:00", $Y, $M);
    foreach ($ee as $departmentName => $staff) {
      $departmentName ||= $runtime->txt->do_template($module, 'officeshifts.department.untitled');
      $deprows = array(); 
      foreach ($staff as $employee) {
        $employee_id = $employee['ID'];
        $dateFired = $employee['dateFired'] || '';
        if ($dateFired == '' || ($dateFired > $firstDay)) { 
          $aa = $empabsences{$employee_id]; 
          if (count($aa) > 0) {
            $employee['absences'] = $objSM->render_employee_shifts($days, $empabsences{$employee_id});
          } else {
            $employee['absences'] = $emptyrow; 
          }
          $deprows[] = dot('officeshifts.employee'.($acc->can_edit_staff($office) || $acc->can_view_staff($office) ? '.edit' : ''), $employee);   
        }
      }
      $rows[] = dot('officeshifts.department', array('name' => $departmentName, 'cnt' => count($deprows), 'colspan' => count($days)));
      $rows += $deprows;
    }
    $pageParams['employees'] = join('', @rows);
  }
}

# register pageview
$runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'officeabsebcecalendar', 'entity_id' => $office, 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

$pageParams['daytypes'] = arr2ref(s2a($module, 'ListCalendarDayTypes', array('office' => $office))); 
$pageParams['pagetitle'] = $runtime->txt->do_template($module, 'pagetitle.officeshifts', $pageParams);
$page->add('css',  $runtime->txt->do_template('main', 'monthmatrix.css');
$page->add('css',  $runtime->txt->do_template('main', 'monthmatrix.legend.css', $pageParams);
$page->add('title', $pageParams['pagetitle']);  
$page->add('main', $runtime->txt->do_template($module, 'officeshifts', $pageParams);

print $runtime->txt->do_template('main', 'index.wide', $page);


?>
