<?php

$_REQUEST['user_id'] ||= $r['userID'];
$user_id = $_REQUEST['user_id'];
$pageParams = array('user_id' => $user_id);
$userInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $user_id));

if (count($userInfo) == 0) {
  $page->add('main', $runtime->txt->do_template($module, 'usertickets.nouser');
} elseif ($user_id == $r['userID'] || $acc->is_superadmin() || $userInfo['deputy_staff'] == $r['userID'] || $userInfo['line_manager'] == $r['userID']) {
  # Prepare search form
  $tickethandlers = $runtime->s2a($module, 'ListTicketHandlers', $pageParams);
  $tickethandlersoptions = genOptions($tickethandlers, 'lngId', 'HandlerName', lavnn('handler'));
  $pageParams['tickethandlers'] = $tickethandlersoptions;
  $ticketprojects = $runtime->s2a($module, 'ListTicketProjects', $pageParams);
  $ticketprojectsoptions = genOptions($ticketprojects, 'id', 'ProjectTitle', lavnn('project'));
  $pageParams['ticketprojects'] = $ticketprojectsoptions;
  $ticketdepartments = $runtime->s2a($module, 'ListTicketDepartments', $pageParams);
  $ticketdepartmentsoptions = genOptions($ticketdepartments, 'team_id', 'team_name', lavnn('department'));
  $pageParams['ticketdepartments'] = $ticketdepartmentsoptions;
  $priorityoptions = $runtime->getSortedDictArr($module, 'priority', lavnn('priority'));
  $pageParams['ticketpriorities'] = $priorityoptions;
  $statusoptions = $runtime->getSortedDictArr($module, 'status4creator', lavnn('status'));
  $pageParams['ticketstatuses'] = $statusoptions;
  
  # Search and render tickets
  $tickets2follow = $runtime->s2a($module, 'ListTickets2Follow', $_REQUEST); 
  if (count($tickets2follow)) {
    $pageParams['tickets2follow'] = $tickets2follow;
    $pageParams['alltickets2follow'] = $runtime->txt->do_template($module, 'tickets2follow.list', $pageParams);
  } else {
    $pageParams['alltickets2follow'] = $runtime->txt->do_template($module, 'tickets2follow.none', $pageParams);
  }
  use ctlDataGrid;
  $basequery = $runtime->spreview($module, 'ListTickets2Follow', $_REQUEST);  print "<!-- $basequery -->";
  $grid1 = new ctlDataGrid($r, 'tickets2follow', $basequery, $module);
  $descriptor = $runtime->txt->get_module_file($module, 'sql/ListTickets2Follow.columns.txt');
  $columns = $grid1->parse_columns_descriptor($descriptor); 
  $grid1->set_columns(@columns);
  $grid1->set_pager(50);
  $grid1->set_width('100%');
  $grid1['rowselection'] = 'id';
  $grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'tickets2follow.listitem'));
  $grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'tickets2follow.none'));
  $pageParams['datagrid'] = $grid1->render();
  $runtime->saveMoment('Finished rendering data grid');
  
  # Add multiple operation controls - in case if grid is not empty
  if ($grid1['length'] > 0) {
    $pageParams['multi'] = $runtime->txt->do_template($module, 'tickets2follow.multi', $pageParams);
  }
  
  if ($user_id == $r['userID']) {
    $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.mytickets2follow', $pageParams);  
    $page->add('main', $runtime->txt->do_template($module, 'mytickets2follow', $pageParams);  
  } else {
    # TODO check if this user is somehow related to this person (HR? LM? DS?) and restrict access
    $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.usertickets2follow', $userInfo);  
    $page->add('main', $runtime->txt->do_template($module, 'usertickets2follow', $pageParams);  
  }
} else {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.usertickets2follow', $userInfo);  
  $page->add('main', $runtime->txt->do_template($module, 'usertickets.denied', $pageParams);
}




?>
