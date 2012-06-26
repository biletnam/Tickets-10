<?php

use Calendar;

$pageParams = array();
$pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'title.relatedstaff');

use objStaffManagement;
$objSM = new objStaffManagement($r);
$line_manager_for = $objSM->line_manager_for($r['userID']);
$deputy_staff_for = $objSM->deputy_staff_for($r['userID']);
$related_staff = array(@line_manager_for, @deputy_staff_for);

$pageParams['line_manager_for'] = $line_manager_for;
$pageParams['deputy_staff_for'] = $deputy_staff_for;
if (count($line_manager_for) > 0 || count($deputy_staff_for) > 0) {
  # Calculate month based on request and current date
  ($Y, $M) = Calendar::determineMonth($_REQUEST);
  $pageParams['Y'] = $Y; $pageParams['M'] = $M;
  # Get all days for this month
  $days = $runtime->s2a($module, 'ListEpochDays', array('Y' => $Y, 'M' => $M));
  $pageParams['days'] = $days;
  $emptyrow = looptmod('staff', 'officeabsences.employee.emptyday', @days);
  # List all absences of related staff, group them by employee
  $absences = $runtime->s2a('staff', 'ListPeopleAbsences', array('ids' => join_column(',', 'lngId', $related_staff), 'Y' => $Y, 'M' => $M));
  $empabsences = slice_array($absences, 'employee');
  
  $rows = array();
  $nocalendars = array();
  foreach $employee (@related_staff) {
    if ($employee['AbsenceCalendarID'] <> '') { # Do not show employees that do not have absence calendar
      $employee_id = $employee['ID'] = $employee['lngId'];
      $dateFired = $employee['dateFired'] || '';
      if ($dateFired == '' || ($dateFired > $firstDay)) { 
        $cnt++; $blockcnt++;
        $aa = $empabsences{$employee_id]; 
        if (count($aa) > 0) {
          $employee['absences'] = $objSM->render_employee_absences($days, $empabsences{$employee_id});
        } else {
          $employee['absences'] = $emptyrow; 
        }
        push @rows, $runtime->txt->do_template('staff', 'officeabsences.employee.edit', $employee);   
        # Also, check if employee has birthday this month
        if ($employee['MonthBirth'] == $M) {
          $D = $employee['DayBirth'];
          push $birthdays{$D}}, $employee;
        } 
      }
    } else {
      push @nocalendars, $employee;
    }
  }

  if (count($birthdays) > 0) {
    push @rows, $runtime->txt->do_template('staff', 'officeabsences.birthdays', array('birthdays' => $objSM->render_employee_birthdays($days, $birthdays)));
  }
  $pageParams['employees'] = join('', $rows);
  $pageParams['daytypes'] = arr2ref(s2a('staff', 'ListCalendarDayTypes', array('office' => $r['userInfo']['lngWorkPlace'])));

  $page->add('css',  $runtime->txt->do_template('main', 'monthmatrix.css');
  $page->add('css',  $runtime->txt->do_template('main', 'monthmatrix.legend.css', $pageParams);
  $page->add('main', $runtime->txt->do_template('main', 'relatedstaff', $pageParams);
} else {  
  $page->add('main', $runtime->txt->do_template('main', 'relatedstaff.none', $pageParams);
}
$runtime->saveMoment(' rendered main part of the page');



# register pageview
$runtime->db->sqlrun('main', 'RegisterPageview', array('entity_type' => 'relatedstaff', 'entity_id' => '', 'viewer_type' => 'U', 'viewer_id' => $r['userID']));



?>
