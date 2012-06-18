<?php

use Calendar;
use objStaffManagement;
$objSM = new objStaffManagement($r);
 
$pageParams = array();
$office = lavnn('office') || $r['userInfo']['lngWorkPlace'];
if ($office <> 0) {
  %pageParams = $runtime->s2r($module, 'GetOfficeDetails', array('office' => $office)); 
  if (count($pageParams) > 0) {
    $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.officeabsences', $pageParams);
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
    # List all absences in the office, group them by employee
    $absences = $runtime->s2a($module, 'ListOfficeAbsences', array('office' => $office, 'Y' => $Y, 'M' => $M));
    $pageParams['absences'] = $absences;
    $empabsences = slice_array($absences, 'employee');
    $staff = $runtime->s2a($module, 'ListStaff', array('office' => $office, 'fired' => '*', 'sort' => 'st.DepartmentName, p.strLastName')); # check fired status in cycle
    $ee = slice_array($staff, 'DepartmentName');
    $emptyrow = loopt('officeabsences.employee.emptyday', @days);
    $rows = array(); $birthdays = array();
    $firstDay = sprintf("%04s-%02s-01 00:00:00", $Y, $M);
    $blockcnt = 0;
    foreach $departmentName (keys %ee) {
      $staff = $ee{$departmentName];
      $departmentName ||= $runtime->txt->do_template($module, 'officeabsences.department.untitled');
      $deprows = array(); $cnt = 0;
      foreach $employee (@staff) {
        if ($employee['AbsenceCalendarID'] <> '') { # Do not show employees that do not have absence calendar
          $employee_id = $employee['ID'];
          $dateFired = $employee['dateFired'] || '';
          if ($dateFired == '' || ($dateFired gt $firstDay)) { 
            $cnt++; $blockcnt++;
            $aa = $empabsences{$employee_id]; 
            if (count($aa) > 0) {
              $employee['absences'] = $objSM->render_employee_absences($days, $empabsences{$employee_id});
            } else {
              $employee['absences'] = $emptyrow; 
            }
            if ($blockcnt > 15) {
  #            push @deprows, dot('officeabsences.daysrow', array('days' => $days));
              $blockcnt = 0;
            }
            push @deprows, dot('officeabsences.employee'.($acc->can_edit_staff($office) || $acc->can_view_staff($office) ? '.edit' : ''), $employee);   
            # Also, check if employee has birthday this month
            if ($employee['MonthBirth'] == $M) {
              $D = $employee['DayBirth'];
              push $birthdays{$D}}, $employee;
            } 
          }
        }
      }
#      push @rows, dot('officeabsences.daysrow', array('days' => $days)) if count($rows) > 1;
      push @rows, dot('officeabsences.department', array('name' => $departmentName, 'cnt' => $cnt, 'colspan' => count($days)));
      @rows = array(@rows, @deprows);
    }
    if (count($birthdays) > 0) {
      push @rows, dot('officeabsences.birthdays', array('birthdays' => $objSM->render_employee_birthdays($days, $birthdays)));
    }
    if ($acc->can_edit_staff($office)) { # provide links for notifying office workers (and maybe more staff) about office absences for specific day
      push @rows, dot('officeabsences.broadcast', array('days' => $days));
    }
    $pageParams['employees'] = join('', @rows);
  }
}

# register pageview
srun('main', 'RegisterPageview', array('entity_type' => 'officeabsebcecalendar', 'entity_id' => $office, 'viewer_type' => 'U', 'viewer_id' => $r['userID']));

$pageParams['daytypes'] = arr2ref(s2a($module, 'ListCalendarDayTypes', array('office' => $office))); 

$page->add('css',  dotmod('main', 'monthmatrix.css');
$page->add('css',  dotmod('main', 'monthmatrix.legend.css', $pageParams);
$page->add('title',  $pageParams['pagetitle'];
$page->add('main', $runtime->txt->do_template($module, 'officeabsences', $pageParams);




?>
