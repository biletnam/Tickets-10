<?php

use Calendar;
$activedate = lavnn("activedate") || Calendar::getToday();
$special = lavnn('special', $_REQUEST, '');
if ($special == 'previous') {
  $activedate = Calendar::addDate($activedate, -1);
} elseif ($special == 'next') { 
  $activedate = Calendar::addDate($activedate, 1);
}
$pageParams = ('activedate' => $activedate);
$date = Calendar::parseDate($activedate);

$office = $pageParams['office'] = lavnn("office", $_REQUEST, 0);
if ($office <> 0) {

  $Y = $pageParams['Y'] = $date['year'];
  $M = $pageParams['M'] = $date['month'];
  $D = $pageParams['D'] = $date['day'];
  
  # List all absences in the office, group them by employee
  $absences = $runtime->s2a($module, 'ListOfficeAbsences', array('office' => $office, 'Y' => $Y, 'M' => $M, 'D' => $D));
  $pageParams['absences'] = $absences;
  $empabsences = Arrays::slice_array($absences, 'employee'); 
  $staff = $runtime->s2a($module, 'ListStaff', array('office' => $office, 'fired' => '*', 'sort' => 'st.DepartmentName, p.strLastName')); # check fired status in cycle
  $ee = Arrays::slice_array($staff, 'DepartmentName');
  $thisDay = sprintf("%04s-%02s-%02s 00:00:00", $Y, $M, $D);
  
  foreach $departmentName (keys %ee) {
    $staff = @{$ee{$departmentName}};
    $departmentName ||= $runtime->doTemplate($module, 'officeabsences.department.untitled');
    $deprows = array(); $cnt = 0; $processed = array();
    foreach $employee (@staff) { 
      if ($employee['AbsenceCalendarID'] <> '') { # Ignore staff without absence calendar 
        $employee_id = $employee['ID'];
        if (!exists $processed{$employee_id}) { # Ignore repeated entries
          $dateFired = $employee['dateFired'] || '';
          if ($dateFired == '' || ($dateFired gt $thisDay)) { 
            $cnt++; $employee['_i_'] = $cnt; $employee['_mod2_'] = $cnt % 2;
            $aa = @{$empabsences{$employee_id}}; 
            if (count($aa) > 0) {
              $hash = %{$aa[0]}; 
              if ($hash['OffDayType'] <> '') { 
                # There is a special day for an office, but employee might have overwork!
                if ($hash['EmpDayType'] == 'OW') {
                  $employee['absence'] = $runtime->doTemplate($module, 'editofficeabsences.employee.overwork', $hash);
                } else {
                  $employee['absence'] = $runtime->doTemplate($module, 'editofficeabsences.employee.officeday', $hash);
                }
              } else {
                $employee['absence'] = $runtime->doTemplate($module, 'editofficeabsences.employee.absence', $hash);
              }
            } else {
              $employee['absence'] = $runtime->doTemplate($module, 'editofficeabsences.employee.select', $$employee); 
            }
            push @deprows, dot('editofficeabsences.employee', $$employee);   
            $processed{$employee_id} = $employee_id; 
          }
        } 
      }
    }
    push @rows, dot('officeabsences.department', array('name' => $departmentName, 'cnt' => $cnt, 'colspan' => 1));
    @rows = (@rows, @deprows);
  }
  $pageParams['employees'] = join('', @rows);
  $absencetypes = $runtime->s2a($module, 'ListOfficeAbsenceTypes', array('office' => $office)); 
  $pageParams['day_type_options'] = arr2ref(genOptions($absencetypes, 'code', 'name', 'V')); 
  $pageParams['qty_options'] = arr2ref($runtime->getDictArr('calendars', 'absence.qty', '1.0'));
}

 
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.editofficeabsences');
$page->add('main', $runtime->doTemplate($module, 'editofficeabsences', $pageParams);

?>
