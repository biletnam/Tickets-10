<?php

$_REQUEST['user_id'] ||= $r['userID'];
$user_id = $_REQUEST['user_id'];
$pageParams = ('user_id' => $user_id);
$userInfo = $runtime->s2r('staff', 'GetEmployeeDetails', array('id' => $user_id)); 

if (count($userInfo) == 0) {
  $page->add('main', $runtime->doTemplate($module, 'usertickets.nouser');
} elseif ($user_id == $r['userID'] || $acc->is_superadmin() || $userInfo['deputy_staff'] == $r['userID'] || $userInfo['line_manager'] == $r['userID']) {
  
  # Render tickets
  use ctlDataGrid;
  $basequery = spreview($module, 'ListTickets2React', $pageParams); print "<!-- $basequery -->";
  $grid1 = new ctlDataGrid($r, 'tickets2react', $basequery, $module);
  $descriptor = $runtime->rf($module, 'sql/ListTickets2React.columns.txt');
  $columns = $grid1->parse_columns_descriptor($descriptor);
  $grid1->set_columns(@columns);
  $grid1->set_pager(50);
  $grid1->set_default_sort(5); # Last act, ascending
  $grid1->set_width('100%');
  $grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'tickets2react.listitem'));
  $grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'tickets2react.none'));
  $pageParams['datagrid'] = $grid1->render();
  $runtime->saveMoment('Finished rendering data grid');
  
  if ($user_id == $r['userID']) {
    $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.mytickets2react', $pageParams);  
    $page->add('main', $runtime->doTemplate($module, 'mytickets2react', $pageParams);  
  } else {
    # TODO check if this user is somehow related to this person (HR? LM? DS?) and restrict access
    $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.usertickets2react', $userInfo);  
    $page->add('main', $runtime->doTemplate($module, 'usertickets2react', $pageParams);  
  }
} else {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.usertickets2react', $userInfo);  
  $page->add('main', $runtime->doTemplate($module, 'usertickets.denied', $pageParams);
}




?>