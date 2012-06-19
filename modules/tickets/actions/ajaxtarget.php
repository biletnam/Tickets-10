<?php

$html = $runtime->txt->do_template($module, 'ajaxtarget.notfound');
$inline = '';
$id = lavnn('ticket', $_REQUEST, 0);
if ($id > 0) {
  $ticketInfo = $objT->get_ticket($id);
  if (count($ticketInfo) > 0) {
    $type = lavnn('type', $_REQUEST, '');
    if ($type == 'meeting') { 
      $meetings = $runtime->s2a('tickets', 'ListMeetings', array('user_id' => $r['userID'])); 
      $_REQUEST['meetingoptions'] = arr2ref(genOptions($meetings, 'id', 'name', ''));
      $inline = $runtime->txt->do_template($module, 'ajaxtarget.meeting', $_REQUEST);
    } elseif ($type == 'employee') { 
      $inline = $runtime->txt->do_template($module, 'ajaxtarget.employee', $_REQUEST);
    } elseif ($type == 'client') { 
      $inline = $runtime->txt->do_template($module, 'ajaxtarget.client', $_REQUEST);
    } elseif ($type == 'contract') { 
      $inline = $runtime->txt->do_template($module, 'ajaxtarget.contract', $_REQUEST);
    } elseif ($type == 'generator') { 
      $inline = $runtime->txt->do_template($module, 'ajaxtarget.generator', $_REQUEST);
    } else {
      $html = $runtime->txt->do_template($module, 'ajaxtarget', $ticketInfo);
    }
  }
}

if ($inline <> '') {
  print $inline;
} else {
  $popupParams = array(
    'title' => "Adding targets for ticket",
    'content' => $html,
  );
  
  print $runtime->txt->do_template('main', 'popup', $popupParams);
}

1;


?>
