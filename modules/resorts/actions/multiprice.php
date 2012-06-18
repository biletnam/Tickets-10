<?php
$ids = get_param_array('ids') || ();
$priceids = join(',', $ids);
$op = lavnn('op', $_REQUEST, '');
$hotelid = lavnn('hotel_id', $_REQUEST, 0);
if ($hotelid > 0) {
  if ($priceids == '') {
    set_cookie('error', 'Please select some prices first!');
    go("?p=$module/edithotel&id=$hotelid&tab=prices");
  } else {
    if ($op == 'delete') { 
      srun($module, 'DeletePricesHistory', array('ids' => $priceids, 'editor' => $r['userInfo']['staff_id']));    
      srun($module, 'DeletePrices', array('ids' => $priceids));
      set_cookie('flash', dot('flash.price.deleted'));
    }
  }
  go("?p=$module/edithotel&id=$hotelid&tab=prices");
} else {
  set_cookie('error', 'No hotel selected, saving price cancelled');
  go("?p=$module/hotels");
}

?>
