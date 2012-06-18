<?php

use objStaffManagement;
$objSM = new objStaffManagement($r);

$op = lavnn('op', $_REQUEST, '');
$ids = array();
while (($request_key, $request_value) = each %_REQUEST) {
  my($prefix, $suffix) = split('_', $request_key);
  if ($prefix == 'id' && $suffix <> '') {
    push @ids, $suffix;
  }
}
if (count($ids) > 0 && $op <> '') {
  if ($op == 'assigncalendar') {
    $officecalendar = lavnn('officecalendar');
    $datesince = lavnn('datesince');
    $dateuntil = lavnn('dateuntil');
    $count_failure = 0; $count_success = 0;
    if ($officecalendar <> '') {
      foreach $id (@ids) {
        $result = $objSM->assign_calendar(('employee' => $id, 'officecalendar' => $officecalendar, 'date_since' => $datesince, 'date_until' => $dateuntil));
        $count_success += $result; 
        $count_failure += (1 - $result);
      }
      set_cookie('flash', "Calendar type assigned to ".$count_success." users") if $count_success > 0;
      set_cookie('error', "Calendar type was not assigned to ".$count_failure." users") if $count_failure > 0;
    }
  } elseif ($op == 'move2office') {
    $office = lavnn('newoffice', $_REQUEST, '');
    if ($office <> '') {
      foreach $id (@ids) {
        $objSM->set_office(('id' => $id, 'office' => $office));
      }      
      set_cookie('flash', count($ids)." users moved to another office");
    } else {
      set_cookie('error', "Could not move employees because current office information is lost");
    }
  } elseif ($op == 'move2department') {
    $department = lavnn('newdepartment', $_REQUEST, '');
    if ($department <> '') {
      foreach $id (@ids) {
        $objSM->set_department(('id' => $id, 'department' => $department));
      }      
      set_cookie('flash', count($ids)." users moved to another department");
    } else {
      set_cookie('error', "Could not move employees because current department information is lost");
    }
  }
} else {
  set_cookie('error', "Select some items in order to run multiple operation");
}

$prevUrl = lavnn('url') || "p=$module/offices";
go("?$prevUrl");

?>
