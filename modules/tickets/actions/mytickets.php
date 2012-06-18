<?php

use ctlTab;
 
$pageParams = array();
$user_id = $pageParams['user_id'] = $r['userInfo']['staff_id'];
$runtime->saveMoment('  fetched list of tickets from db');

  $sqlParams = ('user_id' => $r['userInfo']['staff_id'], 'ticketing_type' => ($r['userInfo']['additionalData']['ticketing_type'] || 1));
  $projects = $runtime->s2a($module, 'ListProjects', $sqlParams);
  $projectoptions = genOptions($projects, 'id', 'title');
  $pageParams['projects'] = $projectoptions;
  $runtime->saveMoment('  fetched projects from db');

  $priorityoptions = $runtime->getSortedDictArr($module, 'priority', $ticketInfo['priority']);
  $pageParams['priorities'] = $priorityoptions;
  $runtime->saveMoment('  got sorted list of priorities');

  # Prepare Handle tab
  $weightedtickets2handle = $runtime->s2a($module, 'ListWeightedTickets2Handle', array('user_id' => $user_id));
  $pageParams['tickets2handle'] = $weightedtickets2handle;
  $runtime->saveMoment('  prepared handle tab');
  
  # Prepare Review tab
  $ticketcreators = $runtime->s2a($module, 'ListReviewTicketCreators', array('user_id' => $user_id));
  $ticketcreatorsoptions = genOptions($ticketcreators, 'lngId', 'CreatorName');
  $pageParams['ticketcreators'] = $ticketcreatorsoptions;
  $ticketprojects = $runtime->s2a($module, 'ListReviewTicketProjects', array('user_id' => $user_id));
  $ticketprojectsoptions = genOptions($ticketprojects, 'id', 'ProjectTitle');
  $pageParams['ticketprojects'] = $ticketprojectsoptions;
  $priorityoptions = $runtime->getSortedDictArr($module, 'priority');
  $pageParams['ticketpriorities'] = $priorityoptions;
  $statusoptions = $runtime->getSortedDictArr($module, 'status4reviewer');
  $pageParams['ticketstatuses'] = $statusoptions;
  $tickets2review = $runtime->s2a($module, 'ListTickets2Review', array('user_id' => $user_id));
  $pageParams['tickets2review'] = $tickets2review;
  $pageParams['alltickets2review'] = $runtime->doTemplate($module, 'tickets2review.list', $pageParams);
  $reviewTab = $runtime->doTemplate($module, 'mytickets.review', $pageParams);
  $runtime->saveMoment('  prepared review tab');
  
  # Prepare Follow tab
  $tickethandlers = $runtime->s2a($module, 'ListTicketHandlers', array('user_id' => $user_id));
  $tickethandlersoptions = genOptions($tickethandlers, 'lngId', 'HandlerName');
  $pageParams['tickethandlers'] = $tickethandlersoptions;
  $ticketprojects = $runtime->s2a($module, 'ListTicketProjects', array('user_id' => $user_id));
  $ticketprojectsoptions = genOptions($ticketprojects, 'id', 'ProjectTitle');
  $pageParams['ticketprojects'] = $ticketprojectsoptions;
  $ticketdepartments = $runtime->s2a($module, 'ListTicketDepartments', array('user_id' => $user_id));
  $ticketdepartmentsoptions = genOptions($ticketdepartments, 'team_id', 'team_name');
  $pageParams['ticketdepartments'] = $ticketdepartmentsoptions;
  $tickets2follow = $runtime->s2a($module, 'ListTickets2Follow', array('user_id' => $user_id));
  $pageParams['tickets2follow'] = $tickets2follow;
  $priorityoptions = $runtime->getSortedDictArr($module, 'priority');
  $pageParams['ticketpriorities'] = $priorityoptions;
  $statusoptions = $runtime->getSortedDictArr($module, 'status4creator');
  $pageParams['ticketstatuses'] = $statusoptions;
  $pageParams['tickets2follow'] = $tickets2follow;
  $pageParams['alltickets2follow'] = $runtime->doTemplate($module, 'tickets2follow.list', $pageParams);
  $followTab = $runtime->doTemplate($module, 'mytickets.follow', $pageParams);
  $runtime->saveMoment('  prepared follow tab');

  # Prepare Notifying tab
  $notifying = $runtime->s2a($module, 'ListUserNotifyingTickets', array('id' => $r['userInfo']['staff_id']));
  $pageParams['notifyingtickets'] = $notifying;
  $runtime->saveMoment('  prepared notifying tab');
  
  # Prepare Closed tab  
  $closedtickets = $runtime->s2a($module, 'ListUserClosedTickets', array('id' => $user_id, 'user_id' => $user_id));
  $pageParams['closedtickets'] = $closedtickets;
  
  # Render tab control
  $tabMyTickets = new ctlTab($r, "tcMyTickets");
  $tabMyTickets->addTab('1', dot('mytickets.handle.tabheader', $pageParams), dot('mytickets.handle.list', $pageParams)) if (count($weightedtickets2handle) > 0);
  $tabMyTickets->addTab('2', dot('mytickets.review.tabheader', $pageParams), $reviewTab) if (count($tickets2review) > 0);
  $tabMyTickets->addTab('3', dot('mytickets.follow.tabheader', $pageParams), $followTab) if (count($tickets2follow) > 0);
  $tabMyTickets->addTab('4', dot('mytickets.notifying.tabheader', $pageParams), dot('mytickets.notifying.list', $pageParams)) if (count($notifying) > 0);
  $tabMyTickets->addTab('5', dot('mytickets.closed.tabheader', $pageParams), dot('mytickets.closed.list', $pageParams), 'right') if (count($closedtickets) > 0);
  $pageParams['tabcontrol'] = $tabMyTickets->getHTML();
  $runtime->saveMoment('  rendered tab control for mytickets');
  
  $page['js'] .= $runtime->doTemplate($module, 'tickets2follow.js', array('user_id' => $user_id));
  $page['js'] .= $runtime->doTemplate($module, 'tickets2review.js', array('user_id' => $user_id));
  $page->add('css',  $runtime->doTemplate($module, 'tickets.css');
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.mytickets', $pageParams);  
  $page->add('main', $runtime->doTemplate($module, 'mytickets.list', $pageParams);  

#  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.mytickets', $pageParams);  
#  $page->add('main', $runtime->doTemplate($module, 'mytickets.none', $pageParams);



?>