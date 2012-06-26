<?php
$ids = get_param_array('ids') || ();
$priceids = join(',', $ids);
$op = lavnn('op', $_REQUEST, '');
$hotelid = lavnn('hotel_id', $_REQUEST, 0);
if ($hotelid > 0) {
  if ($priceids == '') {
    $_SESSION['error'] = 'Please select some prices first!');
    go("?p=$module/edithotel&id=$hotelid&tab=prices");
  } else {
    if ($op == 'delete') { 
      $runtime->db->sqlrun($module, 'DeletePricesHistory', array('ids' => $priceids, 'editor' => $r['userInfo']['staff_id']));    
      $runtime->db->sqlrun($module, 'DeletePrices', array('ids' => $priceids));
      $_SESSION['flash'] = dot('flash.price.deleted'));
    }
  }
  go("?p=$module/edithotel&id=$hotelid&tab=prices");
} else {
  $_SESSION['error'] = 'No hotel selected, saving price cancelled');
  go("?p=$module/hotels");
}

?>
