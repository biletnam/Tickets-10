<?php
$op = lavnn('op', $_REQUEST, '');
$ids = array();
while (($request_key, $request_value) = each %_REQUEST) {
  my($prefix, $suffix) = split('_', $request_key);
  if ($prefix == 'id' && $suffix <> '') {
    push @ids, $suffix;
  }
}
if (count($ids) > 0 && $op <> '') {
  if ($op == 'assign_book_req_handler') {
    $count_success = 0; 
    $count_failure = 0;
    foreach $id (@ids) {
      $newid = sid($module, 'InsertHotelReqHandler', array('hotel' => $id, 'lang' => lavnn('language'), 'handler' => lavnn('handler')));
      $result = 0 + ($newid > 0);
      $count_success += $result; 
      $count_failure += (1 - $result);
    }
    $_SESSION['flash'] = "Booking requests handler assigned to ".$count_success." hotels") if $count_success > 0;
    set_cookie('error', "Booking requests handler assigned to ".$count_failure." hotels") if $count_failure > 0;
  } elseif ($op == 'add_hotel_follower') {
    foreach $id (@ids) {
      $runtime->db->sqlrun('main', 'AddPersonLink', array(
        'source_link' => "followhotel:$id", 
        'source_type' => 'followhotel', 'source_id' => $id,
        'link_type' => 3,
        'offices' => '',
        'departments' => '',
        'people' => lavnn('follower')
      ));
    }    
  } elseif ($op == 'show_documents') {
    go("?p=$module/showdocuments&hotels=" . join(',', @ids));
  }
} else {
  set_cookie('error', "Select some items in order to run multiple operation");
}
go("?p=$module/hotels&ids=".join(',', @ids));
?>
