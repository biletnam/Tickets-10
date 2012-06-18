<?php

use ctlTab;
use objBooking;
$objB = new objBooking($r);

$clientData = array();
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $clientData = $runtime->s2r($module, 'GetClientData', $_REQUEST); 
  $countries = $runtime->s2a($module, 'ListCountries');  
  $clientData['countries'] = arr2ref(genOptions($countries, 'country_id', 'country_name', $clientData['country_id']));
  #print Dumper($clientData);
  $page->add('title',  $clientData['pagetitle'] =  dot('title.viewclient', $clientData);


  # Look up client contracts, bookings and maintenance fees
  $contracts = $objB->list_client_contracts(('client_id' => $id)); 
  $clientData['contracts'] = $contracts;
  $bookings = $runtime->s2a($module, 'ListClientBookings', $clientData);
  $clientData['bookings'] = $bookings;
  $mfees = $runtime->s2a($module, 'ListMaintenanceFees', $clientData);
  $clientData['mfees'] = $mfees;
  $clientData['attachments'] = arr2ref(s2a($module, 'ListClientAttachments', array('id' => $id)));
  # Create a client view tab control
  $page['js'] = dotmod('main', 'tabcontrol.js');
  $page['js'] .= dotmod('main', 'linkpeople.js');
  $page['css'] = dotmod('main', 'tabcontrol.css');
  $page->add('css',  dotmod('main', 'linkpeople.css');
  $tabClientView = new ctlTab($r, "tcClientView");
  $tabClientView->addTab('details', dot('viewclient.details.tabheader'), dot('viewclient.details', $clientData)); 
  $tabClientView->addTab('webaccess', dot('viewclient.webaccess.tabheader'), dot('viewclient.webaccess', $clientData)); 
  $tabClientView->addTab('contracts', dot('viewclient.contracts.tabheader'), dot('viewclient.contracts', $clientData)) if (count($contracts) > 0); 
  $tabClientView->addTab('bookings', dot('viewclient.bookings.tabheader'), dot('viewclient.bookings', $clientData)) if (count($bookings) > 0); 
  $tabClientView->addTab('followers', dot('viewclient.followers.tabheader', $clientData), dot('viewclient.followers.wait', $clientData));
  $tabClientView->addTab('mfees', dot('viewclient.mfees.tabheader'), dot('viewclient.mfees', $clientData)) if (count($mfees) > 0); 
  $tabClientView->addTab('attachments', dot('viewclient.attachments.tabheader', $clientData), dot('viewclient.attachments.wait', $clientData));

  # TODO PP?
  
  $comments = $runtime->s2a($module, 'ListClientComments', $clientData);
  $clientData['comments'] = $comments;
  $clientData['contractoptions'] = arr2ref(genOptions($contracts, 'Contract_Number', 'contract_info'));
#  print spreview($module, 'ListClientComments', $clientData).Dumper($clientData);
  if (count($contracts) > 0) {
    $tabClientView->addTab('comments', dot('viewclient.comments.tabheader', $clientData), dot('viewclient.comments', $clientData)); 
  } else {
    $tabClientView->addTab('comments', dot('viewclient.comments.none.tabheader'), dot('viewclient.nocontracts')); 
  }
  
  $tickets = $runtime->s2a($module, 'ListClientTickets', $clientData);
  $clientData['tickets'] = $tickets;
  $tabClientView->addTab('tickets', dot('viewclient.tickets.tabheader', $clientData), dot('viewclient.tickets', $clientData)) if count($tickets) > 0; 

  $tabClientView->setDefaultTab(lavnn('tab') || 'details');
  $clientData['tabcontrol'] = $tabClientView->getHTML();
  $runtime->saveMoment('  tab control rendered');

  # Render the whole page
  $page->add('main', $runtime->doTemplate($module, 'viewclient', $clientData);
}

$page['js'] .= $runtime->doTemplate($module, 'addcomment.js');





?>
