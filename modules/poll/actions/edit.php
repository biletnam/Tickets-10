<?php

$yesno = $runtime->getSortedDictArr('main', 'yesno'); 

$_REQUEST['user_type'] ||= 'U';
$_REQUEST['user_id'] ||= $r['userID'];

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $pollInfo = $runtime->s2r($module, 'GetPollDetails', $_REQUEST); 
  $runtime->saveMoment('  fetched poll data from db');
  
  $access = 'none';
  if (count($pollInfo) > 0) {
    if ($acc['superadmin'] == 1 || $pollInfo['creator'] == ($r['userID'])) {
      $access = 'edit';
    } elseif ($acc->check_resource("editpoll:$id", $r['userID'])) {
      $access = 'edit';
    } elseif ($acc->check_resource("viewpoll:$id", $r['userID'])) {
      $access = 'view';
    }
  } else {
    $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.notfound', $pageParams);
    $page->add('main', $runtime->txt->do_template($module, 'notfound', $pageParams);
  }
  $runtime->saveMoment('  access checked with result: '.$access);
  
  if ($access == 'none') {
    $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.noaccess', $pageParams);
    $page->add('main', $runtime->txt->do_template($module, 'noaccess', $pageParams);
  } elseif ($access == 'view') { 
    $page->add('title',  $pollInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.view', $pollInfo);
    $pollInfo['questions'] = $objP->render(('id' => $id, 'mode' => 'view')) || 'None.';
    $page->add('main', $runtime->txt->do_template($module, 'view', $pollInfo);
  } elseif ($access == 'edit') { 
    $page->add('title',  $pollInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.edit', $pollInfo);
    $pollInfo['questions'] = $objP->render(('id' => $id, 'mode' => 'edit')) || 'None.';
    $page['js'] .= $runtime->txt->do_template('main', 'tabcontrol.js');
    $page->add('css',  $runtime->txt->do_template('main', 'tabcontrol.css');
    $page['js'] .= $runtime->txt->do_template('main', 'linkpeople.js');
    $page->add('css',  $runtime->txt->do_template('main', 'linkpeople.css');
    use ctlTab;
    $tabEditPoll = new ctlTab($r, "tcEditPoll");
    $pollInfo['lmreviewoptions'] = arr2ref(genOptions($yesno, 'key', 'value', $pollInfo['lm_review']));
    $pollInfo['templateoptions'] = arr2ref(genOptions($yesno, 'key', 'value', $pollInfo['is_template']));
    $pollInfo['hiddenoptions'] = arr2ref(genOptions($yesno, 'key', 'value', $pollInfo['is_hidden']));
    $pollInfo['editableoptions'] = arr2ref(genOptions($yesno, 'key', 'value', $pollInfo['can_edit']));
    $tabEditPoll->addTab('edit', dot('edit.tab.data'), dot('edit.data', $pollInfo)); 
    $tabEditPoll->addTab('questions', dot('edit.tab.questions'), dot('edit.questions', $pollInfo)); 
    $tabEditPoll->addTab('viewers', dot('edit.tab.viewers'), dot('edit.wait.viewers', $pollInfo)) if ($pollInfo['is_template'] || 0) == 0;
    $tabEditPoll->addTab('editors', dot('edit.tab.editors'), dot('edit.wait.editors', $pollInfo));
    $tabEditPoll->setDefaultTab(lavnn('tab') || 'questions');
    $pollInfo['tabcontrol'] = $tabEditPoll->getHTML();
    $runtime->saveMoment('  tab control rendered');
    
    $page->add('main', $runtime->txt->do_template($module, 'edit', $pollInfo);
  }
} else {
  $pageParams['lmreviewoptions'] = arr2ref(genOptions($yesno, 'key', 'value'));
  $pageParams['templateoptions'] = arr2ref(genOptions($yesno, 'key', 'value'));
  $pageParams['editableoptions'] = arr2ref(genOptions($yesno, 'key', 'value'));
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.new', $pageParams);
  $page->add('main', $runtime->txt->do_template($module, 'new', $pageParams);
}





?>
