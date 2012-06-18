<?php

use Calendar;
use objStaffManagement;
$objSM = new objStaffManagement($r);
 
$pageParams = array();
$office = lavnn('office') || $r['userInfo']['lngWorkPlace'];
if ($office <> 0) {
  %pageParams = $runtime->s2r($module, 'GetOfficeDetails', array('office' => $office)); 
  if (count($pageParams) > 0) {
    $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.officeshifts', $pageParams);
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
    $empshifts = Arrays::slice_array($shifts, 'employee');
    $staff = $runtime->s2a($module, 'ListOfficeShiftStaff', array('office' => $office));
    $ee = Arrays::slice_array($staff, 'DepartmentName');
    print count($staff) . ' employees in ' . scalar(keys %ee) . ' departments; ';
    $emptyrow = loopt('officeshifts.employee.emptyday', @days);
    $rows = array(); $birthdays = array();
    $firstDay = sprintf("%04s-%02s-01 00:00:00", $Y, $M);
    foreach $departmentName (keys %ee) {
      $staff = @{$ee{$departmentName}};
      $departmentName ||= $runtime->doTemplate($module, 'officeshifts.department.untitled');
      $deprows = array(); 
      foreach $employee (@staff) {
        $employee_id = $employee['ID'];
        $dateFired = $employee['dateFired'] || '';
        if ($dateFired == '' || ($dateFired gt $firstDay)) { 
          $aa = @{$empabsences{$employee_id}}; 
          if (count($aa) > 0) {
            $employee['absences'] = $objSM->render_employee_shifts($days, $empabsences{$employee_id});
          } else {
            $employee['absences'] = $emptyrow; 
          }
          push @deprows, dot('officeshifts.employee'.($acc->can_edit_staff($office) || $acc->can_view_staff($office) ? '.edit' : ''), $$employee);   
        }
      }
      push @rows, dot('officeshifts.department', array('name' => $departmentName, 'cnt' => count($deprows), 'colspan' => count($days)));
      @rows = (@rows, @deprows);
    }
    $pageParams['employees'] = join('', @rows);
  }
}

# register pageview
srun('main', 'RegisterPageview', array('entity_type' => 'officeabsebcecalendar', 'entity_id' => $office, 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

$pageParams['daytypes'] = arr2ref(s2a($module, 'ListCalendarDayTypes', array('office' => $office))); 

$page->add('css',  dotmod('main', 'monthmatrix.css');
$page->add('css',  dotmod('main', 'monthmatrix.legend.css', $pageParams);
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'pagetitle.officeshifts', $pageParams);
$page->add('main', $runtime->doTemplate($module, 'officeshifts', $pageParams);

print dotmod('main', 'index.wide', $page);


?>
