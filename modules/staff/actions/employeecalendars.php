<?php

$id = lavnn('employee') || $r['userID'];
if ($id > 0) {
  $personInfo = $runtime->s2r($module, 'GetEmployeeDetails', array('id' => $id));
  if (count($personInfo) > 0) {
    # Get list of office calendars
    $calendars = $runtime->s2a($module, 'ListEmployeeOfficeCalendars', array('id' => $id));
    $personInfo['calendars'] = $calendars;
    print dot('employee.view.calendars', $personInfo);
  }
}

?>
