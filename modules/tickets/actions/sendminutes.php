<?php

$id = lavnn('id');
if ($id > 0) {
  use objTicketing;
  $objT = new objTicketing($r);
  $meetingInfo = $runtime->s2r($module, 'GetMeetingData', $_REQUEST);
  if (count($meetingInfo) > 0) {
    $meetingHtml = $objT->print_meeting(%meetingInfo);
    $participants = $acc->list_users_for_resource('meetingparticipants', $id); 
    $to = join_column(', ', 'strLocalOfficeEmail', $participants);

#    $to = 'barcodex@gmail.com';
    
    $subj = $runtime->txt->do_template($module, 'mail.subj.minutes');
    $body = $runtime->txt->do_template($module, 'mail.body.minutes', array('id' => $id, 'body' => $meetingHtml));

    $result = 0;
    if (lavnn('withpdf') > 0) {
      $html = $runtime->txt->do_template('main', 'index.pdf', array('main' => $meetingHtml));
      $pdfname = $r['fs']pdf($html, "Meeting.$id.pdf", 1);
      $f = array('filename' => 'Test.pdf', 'type' => 'application/pdf', 'path' => $pdfname);
      $files = array($f);
      $result = mail_withfiles($to, '', $subj, $body, $files);
    } else {
      $result = mail($to, '', $subj, $body);
    }

    if ($result > 0) {
      $_SESSION['flash'] = "Minutes sent to $to");
    } else {
      set_cookie('error', 'Meeting minutes not sent.');
    }
    
  } else {
    set_cookie('error', 'Meeting not found, minutes not sent.');
  }
}

go("?p=$module/meetings");

?>
