<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  use objTicketing;
  $objT = new objTicketing($r);
  $meetingInfo = $runtime->s2r($module, 'GetMeetingData', $_REQUEST);
  
  # Check for access to meeting data
  $access = $objT->check_access_meeting($id);
  $runtime->saveMoment('  access checked with result: '.$access);

  $page->add('title',  $meetingInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.printmeeting', $meetingInfo);
  if ($access == 'none') {
    $page->add('main', $runtime->txt->do_template($module, 'viewmeeting.noaccess');
  } elseif ($access == 'edit' || $access == 'view') {
    $page->add('main',  $objT->print_meeting(%meetingInfo);
  }
}






?>
