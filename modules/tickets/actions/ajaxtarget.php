<?php

$html = $runtime->doTemplate($module, 'ajaxtarget.notfound');
$inline = '';
$id = lavnn('ticket', $_REQUEST, 0);
if ($id > 0) {
  $ticketInfo = $objT->get_ticket($id);
  if (count($ticketInfo) > 0) {
    $type = lavnn('type', $_REQUEST, '');
    if ($type == 'meeting') { 
      $meetings = $runtime->s2a('tickets', 'ListMeetings', array('user_id' => $r['userID'])); 
      $_REQUEST['meetingoptions'] = arr2ref(genOptions($meetings, 'id', 'name', ''));
      $inline = $runtime->doTemplate($module, 'ajaxtarget.meeting', $_REQUEST);
    } elseif ($type == 'employee') { 
      $inline = $runtime->doTemplate($module, 'ajaxtarget.employee', $_REQUEST);
    } elseif ($type == 'client') { 
      $inline = $runtime->doTemplate($module, 'ajaxtarget.client', $_REQUEST);
    } elseif ($type == 'contract') { 
      $inline = $runtime->doTemplate($module, 'ajaxtarget.contract', $_REQUEST);
    } elseif ($type == 'generator') { 
      $inline = $runtime->doTemplate($module, 'ajaxtarget.generator', $_REQUEST);
    } else {
      $html = $runtime->doTemplate($module, 'ajaxtarget', $ticketInfo);
    }
  }
}

if ($inline <> '') {
  print $inline;
} else {
  $popupParams = (
    'title' => "Adding targets for ticket",
    'content' => $html,
  );
  
  print dotmod('main', 'popup', $popupParams);
}

1;


?>
