<?php

$searchInfo = $_REQUEST;
$page->add('title',  $searchInfo['pagetitle'] =  dot('title.search');
$resultsHtml = '';
use ctlDataGrid;

$creator = lavnn('creator');
$reviewer = lavnn('reviewer');
$handler = lavnn('handler');
# parameters for exception
$notcreator = lavnn('notcreator');
$notreviewer = lavnn('notreviewer');
$nothandler = lavnn('nothandler');
# Translate special search to something else
$specialsearch_category = lavnn('special_category');
$specialsearch_user = lavnn('special_user');
if ($specialsearch_category <> '' && $specialsearch_user <> '') {
  if ($specialsearch_category == 1) { # Personal TODO
    $creator = $_REQUEST['creator'] = $specialsearch_user;
    $reviewer = $_REQUEST['reviewer'] = $specialsearch_user;
    $handler = $_REQUEST['handler'] = $specialsearch_user;
  } elseif ($specialsearch_category == 2) { # Direct to others
    $creator = $_REQUEST['creator'] = $specialsearch_user;
    $reviewer = $_REQUEST['reviewer'] = $specialsearch_user;
    $nothandler = $_REQUEST['nothandler'] = $specialsearch_user;
  } elseif ($specialsearch_category == 3) { # Sent to projects
    $creator = $_REQUEST['creator'] = $specialsearch_user;
    $notreviewer = $_REQUEST['notreviewer'] = $specialsearch_user;
    $nothandler = $_REQUEST['nothandler'] = $specialsearch_user;
  } elseif ($specialsearch_category == 4) { # Reviewer for someone
    $notcreator = $_REQUEST['notcreator'] = $specialsearch_user;
    $reviewer = $_REQUEST['reviewer'] = $specialsearch_user;
    $nothandler = $_REQUEST['nothandler'] = $specialsearch_user;
  } elseif ($specialsearch_category == 5) { # Handler for someone
    $notcreator = $_REQUEST['notcreator'] = $specialsearch_user;
    $notreviewer = $_REQUEST['notreviewer'] = $specialsearch_user;
    $handler = $_REQUEST['handler'] = $specialsearch_user;
  }
}

@allpriorities = $runtime->getSortedDictArr($module, 'priority'); $ids = '';
if (ref($_REQUEST['priorityoption']) == 'ARRAY') {
  $selectedpriorities = $_REQUEST['priorityoption']; 
  $ids = $_REQUEST['priorityoptions'] = join(',', @selectedpriorities); 
} else {
  $ids = $_REQUEST['priorityoptions'] = $_REQUEST['priorityoption'];
}
$searchInfo['prioritycheckboxes'] = arr2ref(genCheckboxes($allpriorities, 'priorityoption', 'key', 'value', $ids));

@allstatuses = $runtime->getSortedDictArr($module, 'status'); $ids = ''; 
if (ref($_REQUEST['statusoption']) == 'ARRAY') {
  $selectedstatuses = $_REQUEST['statusoption']; 
  $ids = $_REQUEST['statusoptions'] = join(',', @selectedstatuses); 
} else {
  $ids = $_REQUEST['statusoptions'] = $_REQUEST['statusoption'];
}
$searchInfo['statuscheckboxes'] = arr2ref(genCheckboxes($allstatuses, 'statusoption', 'key', 'value', $ids));

# Draw form with filters
$statuses = $runtime->getSortedDictArr($module, 'status', lavnn('status')); 
$searchInfo['statusoptions'] = $statuses;
$priorities = $runtime->getSortedDictArr($module, 'priority', lavnn('priority')); 
$searchInfo['priorityoptions'] = $priorities;
$projects = $runtime->s2a($module, 'ListProjects', array('adminmode' => 'yes'));
$searchInfo['projectoptions'] = arr2ref(genOptions($projects, 'id', 'title', lavnn('project')));
$attachmentoptions = $runtime->getSortedDictArr('main', 'yesno', lavnn('has_attachments')); 
$searchInfo['attachmentoptions'] = $attachmentoptions;

if ($notcreator <> '') {
  $creatorData = $runtime->s2r($module, 'GetEmployeeInfo', array('id' => $notcreator));
  $creatorData['formname'] = 'frmSearchTickets';
  $searchInfo['creatorfilter'] = $runtime->txt->do_template($module, 'filter.creator.except', $creatorData);
} elseif ($creator == '') {
  $searchInfo['creatorfilter'] = $runtime->txt->do_template($module, 'filter.creator.undefined');
} else {
  $creatorData = $runtime->s2r($module, 'GetEmployeeInfo', array('id' => $creator));
  $creatorData['formname'] = 'frmSearchTickets';
  $searchInfo['creatorfilter'] = $runtime->txt->do_template($module, 'filter.creator.defined', $creatorData);
}
if ($notreviewer <> '') {
  $reviewerData = $runtime->s2r($module, 'GetEmployeeInfo', array('id' => $notreviewer));
  $reviewerData['formname'] = 'frmSearchTickets';
  $searchInfo['reviewerfilter'] = $runtime->txt->do_template($module, 'filter.reviewer.except', $reviewerData);
} elseif ($reviewer == '') {
  $searchInfo['reviewerfilter'] = $runtime->txt->do_template($module, 'filter.reviewer.undefined');
} else {
  $reviewerData = $runtime->s2r($module, 'GetEmployeeInfo', array('id' => $reviewer));
  $reviewerData['formname'] = 'frmSearchTickets';
  $searchInfo['reviewerfilter'] = $runtime->txt->do_template($module, 'filter.reviewer.defined', $reviewerData);
}
if ($nothandler <> '') {
  $handlerData = $runtime->s2r($module, 'GetEmployeeInfo', array('id' => $nothandler));
  $handlerData['formname'] = 'frmSearchTickets';
  $searchInfo['handlerfilter'] = $runtime->txt->do_template($module, 'filter.handler.except', $handlerData);
} elseif ($handler == '') {
  $searchInfo['handlerfilter'] = $runtime->txt->do_template($module, 'filter.handler.undefined');
} else {
  $handlerData = $runtime->s2r($module, 'GetEmployeeInfo', array('id' => $handler));
  $handlerData['formname'] = 'frmSearchTickets';
  $searchInfo['handlerfilter'] = $runtime->txt->do_template($module, 'filter.handler.defined', $handlerData);
}

$folders = $runtime->s2a($module, 'ListTicketFolders', array('user_id' => $r['userID']));
if (count($folders) > 0) {
  $searchInfo['folderoptions'] = arr2ref(genOptions($folders, 'id', 'name', lavnn('folder')));
  $searchInfo['folderfilter'] = $runtime->txt->do_template($module, 'filter.folders', $searchInfo);
} else {
  $searchInfo['folderfilter'] = $runtime->txt->do_template($module, 'filter.folder.none');
}

if (dot('search.checkfields', $_REQUEST) <> '') {
  $basequery = $runtime->spreview($module, 'SearchTickets', $_REQUEST); #print $basequery;
  $grid1 = new ctlDataGrid($r, 'tickets', $basequery, $module);
  $descriptor = $runtime->txt->get_module_file($module, 'sql/SearchTickets.columns.txt');
  $columns = $grid1->parse_columns_descriptor($descriptor);
  $grid1->set_columns(@columns);
  $grid1->set_pager(50);
  $grid1->set_width('100%');
  $grid1->set_custom_template('datarow' => $runtime->gettmod($module, 'search.listitem'));
  $grid1->set_custom_template('nodata' => $runtime->gettmod($module, 'search.none'));
  $resultsHtml = $grid1->render();
  $resultsHtml = $grid1->render(('rowcount' => 'yes'));
} elseif (exists($_REQUEST['id'])) {
  $resultsHtml = $runtime->txt->do_template($module, 'search.noparameters');
}
  
$searchInfo['results'] = $resultsHtml;
$page->add('main', $runtime->txt->do_template($module, 'search', $searchInfo);




?>
