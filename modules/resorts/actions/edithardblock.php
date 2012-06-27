<?php

use ctlTab;

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
$hotel = lavnn('hotel', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $runtime->s2r($module, 'GetHardBlockInfo', array('id' => $id));
  $hotel = $pageParams['hotel_id'];
} elseif($hotel <> '') {
  %pageParams = array('hotel_id' => $hotel);
}
if ($hotel <> '') {
  $pageParams['hotelinfo'] = hash2ref(s2r($module, 'GetHotelInfo', array('id' => $hotel))); 
  $officeoptions = arr2ref(s2a($module, 'ListHotelBookingOffices', array('id' => $hotel))); 
  $pageParams['offices'] = arr2ref(genOptions($officeoptions, 'lngId', 'strName', $pageParams['office_id'])); 
  #$arrtypeoptions = arr2ref(s2a($module, 'ListAptTypes'));
  #$pageParams['apttypes'] = arr2ref(genOptions($arrtypeoptions, 'type_id', 'type_name', $pageParams['apt_type_id']));
  $localtypeoptions = arr2ref(s2a($module, 'ListLocalTypes', array('hotel_id' => $pageParams['hotel_id'], 'in_use' => 1)));
  $pageParams['localtypes'] = arr2ref(genOptions($localtypeoptions, 'id', 'local_apt_name', $pageParams['local_apt_id']));
}
if ($id <> '') {
  # Create a tab control for hard block
  $page['js'] = $r->txt->do_template('main', 'tabcontrol.js');
  $page['css'] = $r->txt->do_template('main', 'tabcontrol.css');
  $tabHardBlockView = new ctlTab($r, "tcHardBlockView");

  $tabHardBlockView->addTab('edit', dot('hardblock.edit.tabheader'), dot('hardblock.edit', $pageParams));
  #die spreview($module, 'ListHardBlockBookings', array('id' => $id));
  $hbb = $runtime->s2a($module, 'ListHardBlockBookings', array('id' => $id)); 
  $pageParams['bookings'] = $hbb;
  $hbstats = $runtime->s2r($module, 'GetHardBlockStats', array('id' => $id));
  $pageParams['stats'] = $r->txt->do_template($module, 'hardblock.stats', $hbstats);
  $tabHardBlockView->addTab('report', dot('hardblock.report.tabheader'), dot('hardblock.report', $pageParams)); 
  $tabHardBlockView->setDefaultTab(lavnn('tab') || 'edit');
  $pageParams['tabcontrol'] = $tabHardBlockView->getHTML();
  $runtime->saveMoment('  tab control rendered');
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'title.hardblock.edit', $pageParams); 
  $page->add('main', $r->txt->do_template($module, 'hardblock', $pageParams);
} else {
  # Just adding block -> can't show anything but create form
  $pageParams['tabcontrol'] = $r->txt->do_template($module, 'hardblock.edit', $pageParams);
  $pageParams['pagetitle'] = $page->add('title',  $r->txt->do_template($module, 'title.hardblock.add', $pageParams);
  $page->add('main', $r->txt->do_template($module, 'hardblock', $pageParams);
}






?>
