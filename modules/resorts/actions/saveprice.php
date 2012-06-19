<?php
$id = lavnn('id', $_REQUEST, 0);
$hotelid = lavnn('hotel_id', $_REQUEST, 0);
if ($hotelid > 0) {
  if ($id > 0) {
    # Edit
    $runtime->db->sqlrun($module, 'SavePriceUpdate', $_REQUEST);
    $office = lavnn('office', $_REQUEST, '');
    $runtime->db->sqlrun($module, 'SavePriceHistory', array('id' => $id, 'editor' => $r['userInfo']['staff_id']));
  } else {
    # New
    $ids = array();
    if ($officeid == '') {
      # Take all offices related to the hotel
      $offices = $runtime->s2a($module, 'ListHotelBookingOffices', array('id' => $hotelid)); 
      @ids = Arrays::cut_column($offices, 'lngId');
    } else {
      push @ids, $officeid;
    }
    foreach $officeid (@ids) {
      $_REQUEST['office_id'] = $officeid;
      $id = sid($module, 'SavePriceInsert', $_REQUEST);
      $runtime->db->sqlrun($module, 'SavePriceHistory', array('id' => $id, 'editor' => $r['userInfo']['staff_id'])) if $id > 0;
    }
  }
  $_SESSION['flash'] = dot('flash.price.saved'));
  go("?p=$module/edithotel&id=$hotelid&tab=prices");
} else {
  set_cookie('error', 'No hotel selected, saving price cancelled');
  go("?p=$module/hotels");
}

?>
