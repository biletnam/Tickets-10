<?php

$_REQUEST['user_id'] ||= $r['userID'];
$user_id = $_REQUEST['user_id'];
$pageParams = array('user_id' => $user_id);
$userInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $user_id)); 

if (count($userInfo) == 0) {
  $page->add('main', $runtime->txt->do_template($module, 'usertickets.nouser');
} elseif ($user_id == $r['userID'] || $acc->is_superadmin() || $userInfo['deputy_staff'] == $r['userID'] || $userInfo['line_manager'] == $r['userID']) {
  # Prepare search form
  $ticketcreators = $runtime->s2a($module, 'ListReviewTicketCreators', array('user_id' => $user_id));
  $ticketcreatorsoptions = genOptions($ticketcreators, 'lngId', 'CreatorName', lavnn('creator'));
  $pageParams['ticketcreators'] = $ticketcreatorsoptions;
  $ticketprojects = $runtime->s2a($module, 'ListReviewTicketProjects', array('user_id' => $user_id));
  $ticketprojectsoptions = genOptions($ticketprojects, 'id', 'ProjectTitle', lavnn('project'));
  $pageParams['ticketprojects'] = $ticketprojectsoptions;
  $priorityoptions = $runtime->getSortedDictArr($module, 'priority', lavnn('priority'));
  $pageParams['ticketpriorities'] = $priorityoptions;
  $statusoptions = $runtime->getSortedDictArr($module, 'status4reviewer', lavnn('status'));
  $pageParams['ticketstatuses'] = $statusoptions;
  
  # Search and render tickets
  use ctlDataGrid;
  $basequery = $runtime->spreview($module, 'ListTickets2Review', $_REQUEST);  print "<!-- $basequery -->";
  $grid1 = new ctlDataGrid($r, 'tickets2review', $basequery, $module);
  $descriptor = $runtime->txt->get_module_file($module, 'sql/ListTickets2Review.columns.txt');
  $columns = $grid1->parse_columns_descriptor($descriptor);
  $grid1->set_columns(@columns);
  $grid1->set_pager(50);
  $grid1->set_width('100%');
  $grid1['rowselection'] = 'id';
  $grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'tickets2review.listitem'));
  $grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'tickets2review.none'));
  $pageParams['datagrid'] = $grid1->render();
  $runtime->saveMoment('Finished rendering data grid');
  
  # Add multiple operation controls - in case if grid is not empty
  if ($grid1['length'] > 0) {
    $pageParams['multi'] = $runtime->txt->do_template($module, 'tickets2review.multi', $pageParams);
  }
    
  if ($user_id == $r['userID']) {
    $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.mytickets2review', $pageParams);  
    $page->add('main', $runtime->txt->do_template($module, 'mytickets2review', $pageParams);  
  } else {
    # TODO check if this user is somehow related to this person (HR? LM? DS?) and restrict access
    $userInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $user_id)); 
    $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.usertickets2review', $userInfo);  
    $page->add('main', $runtime->txt->do_template($module, 'usertickets2review', $pageParams);  
  }
} else {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.usertickets2review', $userInfo);  
  $page->add('main', $runtime->txt->do_template($module, 'usertickets.denied', $pageParams);
}




?>
