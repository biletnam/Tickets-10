<?php

$pageParams = array();

$edit_resources = $acc->list_user_resources('editpoll', $r['userID']);
$edit_ids = join_column(',', 'source_id', $edit_resources); 
$edit_polls = $edit_ids == '' ? () : s2a($module, 'GetPolls2Edit', array('ids' => $edit_ids));
$pageParams['editpolls'] = $edit_polls; 
$view_resources = $acc->list_user_resources('viewpoll', $r['userID']);
$view_ids = join_column(',', 'source_id', $view_resources); 
$view_polls = $view_ids == '' ? () : s2a($module, 'GetPolls2View', array('ids' => $view_ids, 'user_type' => 'U', 'user_id' => $r['userID']));
$pageParams['viewpolls'] = $view_polls;

use ctlTab;
$tabPolls = new ctlTab($r, 'ctPolls');
$tabPolls->addTab('mypolls', dot('list.tab.mypolls'), dot('mypolls', $pageParams));
$tabPolls->addTab('editlist', dot('list.tab.editlist'), dot('editlist', $pageParams));
$tabPolls->setDefaultTab(lavnn('tab') || 'mypolls');
$pageParams['tabcontrol'] = $tabPolls->getHTML();

$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.list');
$page->add('main', $r->txt->do_template($module, 'list', $pageParams);

$page['js'] .= $r->txt->do_template('main', 'tabcontrol.js');
$page->add('css',  $r->txt->do_template('main', 'tabcontrol.css');



?>
