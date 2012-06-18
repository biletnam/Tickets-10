<?php

$id = lavnn('id', $_REQUEST, '');
$pageParams = array();

if ($id <> '') {
  %pageParams = $runtime->s2r('resorts', 'GetHotelInfo', $_REQUEST);
  $periods = $runtime->s2a('resorts', 'ListHotelPeriods', $_REQUEST);
  $pageParams['periods'] = $periods;
  $pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'hotelperiods.title', $pageParams); 
}

$page->add('main', $runtime->txt->do_template($module, 'hotelperiods', $pageParams);




?>
