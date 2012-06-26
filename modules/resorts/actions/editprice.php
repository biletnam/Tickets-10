<?php
$id = lavnn('id', $_REQUEST, 0);
$hotelid = lavnn('hotel_id', $_REQUEST, 0);
$priceInfo = array();
if ($id > 0) {
  %priceInfo = $runtime->s2r($module, 'GetPriceInfo', array('id' => $id)); 
  $hotelid = $priceInfo['hotel_id'];
  $priceInfo['formtitle'] = $runtime->txt->do_template($module, 'editprice.formtitle.edit');
} else {
  %priceInfo = array('hotel_id' => $hotelid);
  $priceInfo['formtitle'] = $runtime->txt->do_template($module, 'editprice.formtitle.add');
}
if ($hotelid > 0) {
  $officeoptions = arr2ref(s2a($module, 'ListHotelBookingOffices', array('id' => $hotelid))); 
  $priceInfo['alloffices'] = $runtime->txt->do_template($module, 'editprices.alloffices') if ($id == 0);
  $priceInfo['offices'] = arr2ref(genOptions($officeoptions, 'lngId', 'strName', $priceInfo['office_id'])); 
  $arrtypeoptions = arr2ref(s2a($module, 'ListAptTypes'));
  $priceInfo['blockpricetypes'] = arr2ref($runtime->getDictArr($module, 'BlockPriceType'));
  $localtypeoptions = arr2ref(s2a($module, 'ListLocalTypes', array('hotel_id' => $hotelid)));
  $priceInfo['localtypes'] = arr2ref(genOptions($localtypeoptions, 'id', 'local_apt_name', $priceInfo['local_apt_id']));
  $existingcurrencies = $runtime->s2a($module, 'ListExistingCurrencies', array('id' => $hotelid)); # which currencies are already used to define prices for this hotel
  $priceInfo['suggest_price_ccy'] = loopt('editprices.suggestccy.price', @existingcurrencies); 
  $priceInfo['suggest_realprice_ccy'] = loopt('editprices.suggestccy.realprice', @existingcurrencies); 
  $topccy = $existingcurrencies[0]; # Top currency is used for preselecting currency lists.
  $priceInfo['currencies'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListCurrencies')), 'currency_id', 'currency_name', array($priceInfo['currency_id'] || $topccy['currency_id'])));  
  $priceInfo['realcurrencies'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListCurrencies')), 'currency_id', 'currency_name', array($priceInfo['real_currency_id'] || $topccy['currency_id'])));  
  print $runtime->txt->do_template($module, 'editprices.form', $priceInfo);
}
?>
