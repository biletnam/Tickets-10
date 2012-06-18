<?php
use objBooking;
$objB = new objBooking($r);

$sqlParams = %_REQUEST; 
$morepeople = lavnn('morepeople'); 
$gen_user_id = $runtime->get_cookie('gen_user_id', $_REQUEST, 0);
$bookreq_id = 0;
if ($gen_user_id > 0) {
  $genUserInfo = $runtime->s2r($module, 'GetGeneratorUserInfo', array('id' => $gen_user_id)); 
  $gen_id = $sqlParams['generator_id'] = $genUserInfo['generator_id'];
  $generatorInfo = $runtime->s2r($module, 'GetGeneratorInfo', $genUserInfo);
  $sqlParams['generator_name'] = $generatorInfo['generator_name'];
  $hotelInfo = $runtime->s2r($module, 'GetHotelInfo', array('id' => $sqlParams['hotel']));
  $sqlParams['hotel_name'] = $hotelInfo['hotel_name'];
  $langInfo = $runtime->s2r($module, 'GetLangInfoByCode', array('code' => $lang));
  $langid = $sqlParams['lang_id'] = array($langInfo['id'] || 0);
  # Gather data about MorePeople coming - this data will be packed into one special field
  $moreguests = array();
  foreach $n (@morepeople) {
    $person = array(
      'fname' => $runtime->urlencode((lavnn("fname_$n") || '')),
      'lname' => $runtime->urlencode((lavnn("lname_$n") || '')),
      'rel' => (lavnn("rel_$n") || ''),
      'job' => (lavnn("job_$n") || ''),
      'passport' => (lavnn("passport_$n") || ''),
      'dob' => (lavnn("dob_$n") || ''),
    );
    push @moreguests, $person;
  }

  # Now, try to create a record
#  $bookreq_id = $sqlParams['bookreq'] = sid($module, 'InsertBookingRequest', $sqlParams);
  $sqlResults = $objB->save_booking_request_from_generator(%sqlParams);
  if (count($sqlResults) > 0) {
    $bookreq_id = $sqlParams['bookreq'] = $sqlParams['bookreq_id'] = $sqlResults['id'] || 0;
    if ($bookreq_id > 0) {
      # TODO update request with info about first guests
      $objB->set_booking_request_first_guests(%sqlParams);
      # update request with info about more guests
      if (count($morepeople) > 0) {
        $objB->set_booking_request_more_guests($bookreq_id, @moreguests);
      }
      # send notification to dashboards of request handlers
      use objNotification;
      $objN = new objNotification($r);
      $subject = $runtime->txt->do_template($module, 'bookreq.notification.subject', $sqlParams);
      $digest = $runtime->txt->do_template($module, 'bookreq.notification.digest', $sqlParams);
      $nid = $objN->add_notification('bookreq', $bookreq_id, $subject, $digest);
      $reqhandlers = $runtime->s2a($module, 'ListRequestHandlers', $sqlParams);
      if (count($reqhandlers) > 0) {
        $objN->distribute_notification($nid, @reqhandlers);
      }
      # also send a confirmation mail to genergator (sender)
      $mailsent = mail(
        $sqlParams['sender_email'], 
        '', 
        dot('bookreq.generatormail.subject', $sqlParams), 
        dot('bookreq.generatormail.body', $sqlParams)
      );
      # redirect to the list of requests
      $urlsuccess = "?p=$module/listrequests&gen_user_id=$gen_user_id#top";
      $runtime->set_cookie('flash', 'New booking request registered ');
      go($urlsuccess);
      exit();
    } else {
      $runtime->set_cookie('error', 'Could not insert booking request ' . $sqlResults['err']);
    }
  } else {
    $runtime->set_cookie('error', 'Could not insert booking request');
  }
}

# Prepare URL for the case of failure
delete $_REQUEST['f']; 
$querystring = "?p=$module/newrequest&".Arrays::build_query_string(%_REQUEST);
$urlfailure = lavnn('urlfailure') || $querystring;
# Redirect to next page
go($urlfailure.'#top');

?>
