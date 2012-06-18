<?php
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  use objTicketing;
  $objT = new objTicketing($r);
  $meetingInfo = $runtime->s2r($module, 'GetMeetingData', $_REQUEST);

  # Check for access to meeting data
  $access = 'none';
  if ($acc['superadmin'] == 1 || $meetingInfo['creator'] == $r['userID']) {
    $access = 'edit';
  } elseif ($acc->check_resource("meetingparticipants:$id", $r['userID'])) {
    $access = 'edit';
  } elseif ($acc->check_resource("meetingviewers:$id", $r['userID'])) {
    $access = 'view';
  }
  $runtime->saveMoment('  access checked with result: '.$access);

  if ($access == 'none') {
    $meetingInfo['tabcontrol'] = $runtime->txt->do_template($module, 'viewmeeting.noaccess');
  } elseif ($access == 'edit') { 
    # Prepare tab control with editing controls
    use ctlTab;
    $tabMeetingView = new ctlTab($r, "tcMeetingView");
    $tabMeetingView->addTab('details', dot('viewmeeting.details.tabheader'), dot('viewmeeting.details', $meetingInfo)); 
    $tabMeetingView->addTab('participants', dot('viewmeeting.participants.tabheader'), dot('viewmeeting.participants', $meetingInfo)); 
    $tabMeetingView->addTab('viewers', dot('viewmeeting.viewers.tabheader'), dot('viewmeeting.viewers', $meetingInfo)); 
    # Prepare list of issues, list of participants and list of priorities 
    $issues = $runtime->s2a($module, 'ListMeetingTickets', $_REQUEST);
    $meetingInfo['issues'] = $issues;
    $participants = $acc->list_users_for_resource('meetingparticipants', $id);
    $participantoptions = genOptions($participants, 'lngId', 'strNick');
    $meetingInfo['participants'] = arr2ref(genCheckboxes($participants, 'notified', 'lngId', 'strNick'));
    $meetingInfo['participantoptions'] = $participantoptions;
    $priorityoptions = $runtime->getSortedDictArr($module, 'priority', 0);
    $meetingInfo['priorityoptions'] = $priorityoptions;
    $meetings = $runtime->s2a($module, 'ListMeeting2Copy', array('user_id' => $r['userID']));
    $meetingInfo['copyfromoptions'] = arr2ref(genOptions($meetings, 'id', 'name', $copyfrom));
    $tabMeetingView->addTab('issues', dot('viewmeeting.issues.tabheader', $meetingInfo), dot('viewmeeting.issues', $meetingInfo)); 
    # render prepared tab control
    $tabMeetingView->setDefaultTab(lavnn('tab') || 'details'); 
    $meetingInfo['tabcontrol'] = $tabMeetingView->getHTML();
    $runtime->saveMoment('  tab control rendered');
  } elseif ($access == 'view') {
    $issues = $runtime->s2a($module, 'ListMeetingTickets', $_REQUEST);
    $meetingInfo['issues'] = $issues;
    $meetingInfo['tabcontrol'] = $runtime->txt->do_template($module, 'viewmeeting.issues.view', $meetingInfo);
  }   

  
  $page->add('title',  $meetingInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.viewmeeting', $meetingInfo);
  $page->add('main', $runtime->txt->do_template($module, 'viewmeeting', $meetingInfo);
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  $page['js'] .= dotmod('main', 'linkpeople.js');
  $page->add('css',  dotmod('main', 'linkpeople.css');
} else {
  $page->add('title',  $meetingInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.viewmeeting.notfound');
}


?>
