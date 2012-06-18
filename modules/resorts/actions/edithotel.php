<?php

use ctlTab;

$pageParams = array();
$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  %pageParams = $runtime->s2r('resorts', 'GetHotelInfo', array('id' => $id));
  $pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'edithotel.title', $pageParams); 
  $pageParams['locations'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListLocations')), 'id', 'location_name', $pageParams['location_id']));
} else {
  $pageParams['pagetitle'] = $page->add('title',  $runtime->txt->do_template($module, 'edithotel.new.title', $pageParams); 
  $pageParams['locations'] = arr2ref(genOptions(arr2ref(s2a($module, 'ListLocations')), 'id', 'location_name'));
}

$page['js'] .= dotmod('main', 'tabcontrol.js');
$page->add('css',  dotmod('main', 'tabcontrol.css');
$page['js'] .= dotmod('main', 'linkpeople.js');
$page->add('css',  dotmod('main', 'linkpeople.css');
  
$tabEditHotel = new ctlTab($r, "tcEditHotel");
$tabEditHotel->addTab('edit', dot('edithotel.edit.tabheader'), dot('edithotel.edit', $pageParams));
if ($id <> '') {
  # Edit offices where the hotel belongs to
  $alloffices = $runtime->s2a($module, 'ListAllBookingOffices');
  $hoteloffices = $runtime->s2a($module, 'ListHotelBookingOffices', array('id' => $id));
  $ids = join_column(',', 'lngId', $hoteloffices);
  $pageParams['id'] = $id;
  $pageParams['offices'] = arr2ref(genCheckboxes($alloffices, 'office', 'lngId', 'strName', $ids));
  $tabEditHotel->addTab('offices', dot('edithotel.offices.tabheader'), dot('edithotel.offices', $pageParams));

  #Edit followers - people who receive notifications about hotel
  $tabEditHotel->addTab('followers', dot('edithotel.followers.tabheader', $articleInfo), dot('edithotel.followers.wait', $pageParams));

  # Get list of hotel attachments
  $attachments = $runtime->s2a($module, 'ListHotelAttachments', array('id' => $id));
  $pageParams['attachments'] = $attachments;
  $doctypes = $runtime->s2a('admin', 'ListDocTypes');
  $pageParams['doctypeoptions'] = arr2ref(genOptions($doctypes, 'id', 'name'));
  $tabEditHotel->addTab('attachments', dot('edithotel.attachments.tabheader', $pageParams), dot('edithotel.attachments', $pageParams));

  # Get list of local apartment types
  $localtypes = $pageParams['localtypes'] = arr2ref(s2a($module, 'ListLocalTypes', array('hotel_id' => $id))); 
  $tabEditHotel->addTab('localtypes', dot('edithotel.localtypes.tabheader'), dot('edithotel.localtypes', $pageParams)); 

  # Get list of hard blocks
  $hb = $runtime->s2a($module, 'ListHotelHardBlocks', array('id' => $id)); 
  $pageParams['hb'] = $hb;  
  $tabEditHotel->addTab('hb', dot('edithotel.hb.tabheader'), dot('edithotel.hb', $pageParams));

  # Tabs for editing prices and profitability reports
  $tabEditHotel->addTab('prices', dot('edithotel.prices.tabheader'), dot('edithotel.prices.wait')); # makes AJAX call 
  $tabEditHotel->addTab('profitability', dot('edithotel.profitability.tabheader'), dot('edithotel.profitability.wait')); # makes AJAX call 
  
  

}
$tabEditHotel->setDefaultTab(lavnn('tab') || 'edit'); 
$pageParams['tabcontrol'] = $tabEditHotel->getHTML();
$runtime->saveMoment('  tab control rendered');

$page->add('main', $runtime->txt->do_template($module, 'edithotel', $pageParams);



?>
