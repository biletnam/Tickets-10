<?php

use ctlTab;

$generatorData = array();
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $generatorData = $runtime->s2r($module, 'GetGeneratorData', $_REQUEST); 
  $page->add('title',  $generatorData['pagetitle'] =  dot('title.view', $generatorData);

  # Create a client view tab control
  $page['js'] = dotmod('main', 'tabcontrol.js');
  $page['js'] .= dotmod('main', 'linkpeople.js');
  $page['css'] = dotmod('main', 'tabcontrol.css');
  $page->add('css',  dotmod('main', 'linkpeople.css');
  $tabGeneratorView = new ctlTab($r, "tcGeneratorView");
  
  # edit details form
  $emails = Arrays::list2array(arr2ref(split(';', $generatorData['email'])), 'email'); 
  $generatorData['emails'] = (count($emails) > 0) ? loopt('view.details.email', @emails) : dot('view.details.email');
  $tabGeneratorView->addTab('details', dot('view.details.tabheader'), dot('view.details', $generatorData)); 

  # web access for this generator  
  $generatorData['webusers'] = arr2ref(s2a($module, 'ListGeneratorUsers', array('id' => $id)));
  $generatorData['loginfailures'] = arr2ref(s2a($module, 'ListLoginFailures', array('id' => $id)));
  $tabGeneratorView->addTab('webaccess', dot('view.webaccess.tabheader'), dot('view.webaccess', $generatorData));
  
  $alloffices = $runtime->s2a($module, 'ListAllBookingOffices');
  $generatoroffices = $runtime->s2a($module, 'ListGeneratorBookingOffices', array('id' => $id)); # print Dumper($generatoroffices);
  $ids = Arrays::join_column(',', 'lngId', $generatoroffices); 
  $generatorData['offices'] = arr2ref(genCheckboxes($alloffices, 'office', 'lngId', 'strName', $ids));
  $tabGeneratorView->addTab('offices', dot('view.offices.tabheader'), dot('view.offices', $generatorData));
     
  $generatorData['projectoptions'] = arr2ref(s2a($module, 'ListGeneratorProjects', array('id' => $id)));
  $generatorData['comments'] = arr2ref(s2a($module, 'ListGeneratorComments', array('id' => $id)));
  $tabGeneratorView->addTab('comments', dot('view.comments.tabheader', $generatorData), dot('view.comments', $generatorData));

  $tickets = $runtime->s2a($module, 'ListGeneratorTickets', $generatorData);
  $generatorData['tickets'] = $tickets; 
  $tabGeneratorView->addTab('tickets', dot('view.tickets.tabheader', $generatorData), dot('view.tickets', $generatorData)) if count($tickets) > 0; 
  $tabGeneratorView->addTab('attachments', dot('view.attachments.tabheader', $generatorData), dot('view.attachments.wait', $generatorData));
  $tabGeneratorView->addTab('followers', dot('view.followers.tabheader', $generatorData), dot('view.followers.wait', $generatorData));
  $generatorData['requests'] = arr2ref(s2a($module, 'ListGeneratorRequests', array('id' => $id)));
  $tabGeneratorView->addTab('requests', dot('view.requests.tabheader', $generatorData), dot('view.requests', $generatorData));

  $pageviews = $runtime->s2a($module, 'ListGeneratorPageviews', array('id' => $id));
  $generatorData['pageviews'] = $pageviews;
  $tabGeneratorView->addTab('pageviews', dot('view.pageviews.tabheader', $generatorData), dot('view.pageviews', $generatorData)) if count($pageviews) > 0;

  $tabGeneratorView->setDefaultTab(lavnn('tab') || 'details');
  $generatorData['tabcontrol'] = $tabGeneratorView->getHTML();
  $runtime->saveMoment('  tab control rendered');

  # Render the whole page
  $page->add('main', $runtime->doTemplate($module, 'view', $generatorData);
}
$page['js'] .= $runtime->doTemplate($module, 'view.addcomment.js');




?>