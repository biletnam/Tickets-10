<?php
$page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.meetings');
$meetings = $runtime->s2a($module, 'ListMeetings', array('user_id' => $r['userID']));
#print spreview($module, 'ListMeetings', array('user_id' => $r['userID']));
$pageParams['meetings'] = $meetings; 

  use ctlDataGrid;
  
  $basequery = $runtime->spreview($module, 'ListMeetings', array('user_id' => $r['userID'])); 
  $grid1 = new ctlDataGrid($r, 'meetings', $basequery, $module);
  $descriptor = $runtime->txt->get_module_file($module, 'sql/ListMeetings.columns.txt');
  $columns = $grid1->parse_columns_descriptor($descriptor);
  $grid1->set_columns(@columns);
  $grid1->set_pager(50);
  $grid1->set_width('100%');
  $grid1->set_custom_template('actions' => $runtime->gettmod($module, 'meetings.listitem.actions'));
  $pageParams['datagrid'] = $grid1->render();
  $runtime->saveMoment('Finished rendering data grid');

$page->add('main', $runtime->txt->do_template($module, 'meetings', $pageParams);


?>
