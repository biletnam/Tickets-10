<?php

$id = lavnn('hotel', $_REQUEST, 0);
if ($id > 0) {
  $price = lavnn('price', $_REQUEST, 0);
  $price_currency = lavnn('price_currency', $_REQUEST, 0);
  $periods = lavnn('apt_period'); 
  $op = lavnn('op', $_REQUEST, '');
  if ($op == 'set') {
    if ($price == 0) {
      $runtime->set_cookie('flash', 'Please specify all the fields including the price');
    } elseif($price_currency == 0) {
      $runtime->set_cookie('flash', 'Please specify all the fields including the currency');
    } elseif(count($periods) == 0) {
      $runtime->set_cookie('flash', 'Please check at least one of the apartments/periods');
    } else {
      foreach $apt_period (@periods) {
        ($apttype, $period) = split('_', $apt_period, 2);
        $result = $runtime->s2r($module, 'SetHotelApartmentPrice', array('apt_type' => $apttype, 'period' => $period, 'price' => $price, 'ccy' => $price_currency));
      }
    } 
  } elseif ($op == 'clear') {
      foreach $apt_period (@periods) {
        ($apttype, $period) = split('_', $apt_period, 2);
        srun($module, 'DeleteHotelApartmentPrice', array('apt_type' => $apttype, 'period' => $period));
      }
  }
  go("?p=resorts/edithotelprices&id=$id");
} else {
  $runtime->set_cookie('flash', 'No hotel selected for setting the price');
  go("?p=resorts/hotels");
}


?>
