<?php

$hotel = lavnn('id', $_REQUEST, 0);
if ($hotel > 0) {
  $existing = Arrays::cut_column(arr2ref(s2a($module, 'ListHotelBookingOffices', array('id' => $hotel))), 'lngId');
  $ids = join(',', lavnn('office'));
  if (join(',', @existing) <> $ids) {
    foreach $id (lavnn('office')) {
      if (!in_array($id, $existing)) {
        srun($module, 'InsertHotelBookingOffice', array('office' => $id, 'hotel' => $hotel));
      }
    }
    srun($module, 'DeleteObsoleteHotelBookingOffices', array('offices' => $ids, 'hotel' => $hotel));
  }
  go("?p=$module/edithotel&id=$hotel");
} else {
  go("?p=$module/hotels");
}

1;
?>
