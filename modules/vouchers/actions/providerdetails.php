<?php

use objVouchers;
$objVouchers = new objVouchers($r);

$provider = lavnn('id');
if ($provider > 0) {
  $providerInfo = $runtime->s2r($module, 'GetVoucherProviderData', $_REQUEST); 
  $series = $objVouchers->list_provider_series(('id' => $provider)); 
  $providerInfo['series'] = $series;
  $locationoptions = genOptions(arr2ref(s2a($module, 'ListLocations')), 'id', 'location_name', $providerInfo['location']);
  $providerInfo['locationoptions'] = $locationoptions;
  $page->add('title',  $providerInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.providerdetails', $providerInfo);
  $page->add('main', $runtime->txt->do_template($module, 'providerdetails', $providerInfo);
}



  
?>
