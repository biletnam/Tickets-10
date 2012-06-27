<?php

$workflowInfo  = array();
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %workflowInfo = $runtime->s2r($module, 'GetWorkflowInfo', $_REQUEST);
  $page->add('title',  $workflowInfo['pagetitle'] = $r->txt->do_template($module, 'edit.title.edit', $workflowInfo);
  
  $page['js'] .= $r->txt->do_template('main', 'tabcontrol.js');
  $page->add('css',  $r->txt->do_template('main', 'tabcontrol.css');
  use ctlTab;
  $tabEditWorkflow = new ctlTab($r, "tcEditWorkflow");
  $tabEditWorkflow->addTab('info', dot('edit.tab.info'), dot('edit.info', $workflowInfo)); 
  $tabEditWorkflow->addTab('inputs', dot('edit.tab.inputs'), dot('edit.inputs', $workflowInfo)); 
  $tabEditWorkflow->addTab('actions', dot('edit.tab.actions'), dot('edit.actions', $workflowInfo)); 
  $tabEditWorkflow->setDefaultTab(lavnn('tab') || 'questions');
  $workflowInfo['tabcontrol'] = $tabEditWorkflow->getHTML();
  $runtime->saveMoment('  tab control rendered');
  
} else {
  $page->add('title',  $workflowInfo['pagetitle'] = $r->txt->do_template($module, 'edit.title.new');
}
$page->add('main', $r->txt->do_template($module, 'editworkflow', $workflowInfo);



  
?>
