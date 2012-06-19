<?php

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {

  $prices = $runtime->s2a($module, 'ListHotelPrices', $_REQUEST);
  $pageParams['prices'] = $prices;
  $pageParams['p'] = $runtime->txt->do_template($module, 'editprices', $pageParams);

  $pageParams['id'] = $id;
  # Get hotel periods and build an array of their ids to have the same sequence for data rows
  $periods = $runtime->s2a($module, 'ListHotelPeriods', $_REQUEST);
  $ids = Arrays::cut_column($periods, 'id');
  # Generate the row header
  $pageParams['rowheader'] = $runtime->txt->do_template($module, 'edithotelprices.headerrow', array('periods' => arr2ref(@periods)));
  # Generate the data rows for both HB and SB
  $pageParams['hbrows'] = prepare_datarows($id, 'HB', @ids); # Look lib.pl
  $pageParams['sbrows'] = prepare_datarows($id, 'SB', @ids); # Look lib.pl
  $pageParams['currencies'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListCurrencies')), 'currency_id', 'currency_name'));
  # Also, fetch hotel information for showing on the page when needed 
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $id)));
}

$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'edithotelprices.title', $pageParams);
$page->add('css',  $runtime->txt->do_template($module, 'hotels.css');
$page->add('main', $runtime->txt->do_template($module, 'edithotelprices', $pageParams);





  
?>
