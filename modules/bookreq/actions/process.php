<?php

use objBooking;
$objB = new objBooking($r);

$id = lavnn('id', $_REQUEST, 0);
$cid1 = '';
if ($id <> 0) {
  # Look if we know contract id. If yes, reuse its client id. If no, check explicitly given client id
  $contract_id = lavnn('contract_id', $_REQUEST, 0);
  if ($contract_id > 0) {
    $clientInfo = $objB->lookup_client_by_contract(%_REQUEST);
    $cid1 = $clientInfo['client_id'];
  } else {
    $cid1 = lavnn('clientid', $_REQUEST, '');
  }
  # create a new client if none was provided or calculated
  if ($cid1 == '') {
    $_REQUEST['source_type'] = 6; # value for "generator web request" from client_source table
#    formdebug($_REQUEST); print spreview($module, 'InsertFirstGuest', $_REQUEST);
    $cid1 = $_REQUEST['clientid'] = sid($module, 'InsertFirstGuest', $_REQUEST); 
  }
  # client is known - we can make a booking now
  if ($cid1 > 0) {
    $clientInfo = $runtime->s2r($module, 'GetClientData', array('id' => $cid1));
    $_REQUEST['ref_no'] = $clientInfo['ref_no'];
    # If contract was given, generator is marked as sender
    if ($contract_id) {
      $_REQUEST['sender_id'] = $_REQUEST['generator_id'];
      # TODO: put correct generator_id - is it any algorythm out there?
      #$_REQUEST['generator_id'] = ''; 
    }
    # Finally, data gathered to create a booking
    $_REQUEST['entered_by'] = $r['userID'];
    $bookid = sid($module, 'InsertBooking', $_REQUEST);
    if ($bookid > 0) {
      # Add family members
      $sqlParams = (
        'book_id' => $bookid, 'first_name' => $_REQUEST['fname1'], 'last_name' => $_REQUEST['lname1'], 'rel' => $_REQUEST['rel1'],
        'passport' => $_REQUEST['passport1'], 'job' => $_REQUEST['occupation1'], 'date_birth' => $_REQUEST['dob1']
      );
      $id1 = sid($module, 'InsertFamilyGuest', $sqlParams) if ($_REQUEST['fname1'] . $_REQUEST['lname1'] <> '');
      $sqlParams = (
        'book_id' => $bookid, 'first_name' => $_REQUEST['fname2'], 'last_name' => $_REQUEST['lname2'], 'rel' => $_REQUEST['rel2'], 
        'passport' => $_REQUEST['passport2'], 'job' => $_REQUEST['occupation2'], 'date_birth' => $_REQUEST['dob2']
      );
      $id2 = sid($module, 'InsertFamilyGuest', $sqlParams) if ($_REQUEST['fname2'] . $_REQUEST['lname2'] <> '');
      # for the moment, we just use 'morepeople' data as it comes from XML, with no modifications done using the form
      if ($_REQUEST['morepeople'] <> '') {
        $morepeople = Arrays::xml2a($_REQUEST['morepeople'], 'person');
        foreach $fm (@morepeople) {
          %sqlParams = (
            'book_id' => $bookid, 'first_name' => $runtime->urldecode($fm['fname']), 'last_name' => $runtime->urldecode($fm['lname']), 
            'rel' => $fm['rel'],
            'passport' => $fm['passport'], 'date_birth' => $fm['dob']
          );
          $id2 = sid($module, 'InsertFamilyGuest', $sqlParams) if ($fm['fname'] . $fm['lname'] <> '');
        }
      }
      # Change status of request and link it to new booking
      srun($module, 'PromoteBookreq', array('id' => $_REQUEST['id'], 'book_id' => $bookid));
      # Send a notification to generator about request converted to booking
      use objNotification;
      $objN = new objNotification($r);
      $bookingData = $objB->get_booking(('id' => $id));
      $notification_id = $objN->add_notification('createbooking', $bookid, dot('notification.createbooking.subject', $bookingData), dot('notification.createbooking.digest', $bookingData));
      if ($notification_id > 0) {
        $not_reci_id = $objN->add_notification_recipient($notification_id, 0, $_REQUEST['sender_name'], $_REQUEST['sender_email']);
      }
    } else {
      set_cookie('error', 'Could not create a booking');
    }
  } else {
    set_cookie('error', 'Could not create a client for a booking');
  }
  go("?p=$module/view&id=$id");
} else {
  set_cookie('error', 'Invalid booking request was requested to be converted');
  go("?p=$module/list");
}


?>
