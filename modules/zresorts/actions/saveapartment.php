<?php

$id = lavnn('id', $_REQUEST, 0);
$hotel = lavnn('hotel', $_REQUEST, 0);

if ($id > 0) {
  srun($module, 'UpdateHotelApartment', $_REQUEST);
  $hotelInfo = $runtime->s2r($module, 'GetHotelApartmentInfo', array('id' => $id));
  $hotel = $hotelInfo['hotel'];
  set_cookie('flash', dot('flash.apartment.updated'));
} else {
  $names = lavnn('names', $_REQUEST, '');
  if ($names <> '') {
    $apttype = lavnn('apartment_type'); 
    $cnt = 0;
    foreach $name (split('\n', $names)) {
      $hash = ('hotel' => $hotel, 'apartment_type' => $apttype, 'name' => $name);
      $id = sid($module, 'InsertHotelApartment', $hash);
      $cnt++ if ($id > 0);
    }    
    set_cookie('flash', dot('flash.apartments.added', array('cnt' => $cnt))) if $cnt > 0;
  }
}

if ($hotel > 0) {
  go("?p=resorts/hbsb&id=$hotel");
} else {
  go("?p=resorts/hotels");
}

1;

?>
