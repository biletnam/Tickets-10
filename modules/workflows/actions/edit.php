<?php

$workflowInfo  = array();
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %workflowInfo = $objW->get_workflow(%_REQUEST);
  $page->add('title',  $workflowInfo['pagetitle'] = $runtime->doTemplate($module, 'edit.title.edit', $workflowInfo); 
  $workflowInfo['parameters'] = arr2ref($objW->get_parameters(%_REQUEST));  
  $workflowInfo['constants'] = arr2ref($objW->get_constants(%_REQUEST));  
  $workflowInfo['actions'] = arr2ref($objW->get_actions(%_REQUEST));  
  
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  use ctlTab;
  $tabEditWorkflow = new ctlTab($r, "tcEditWorkflow");
#  $pollInfo['lmreviewoptions'] = arr2ref(genOptions($yesno, 'key', 'value', $workflowInfo['lm_review']));
  $tabEditWorkflow->addTab('info', dot('edit.tab.info'), dot('edit.info', $workflowInfo)); 
  $tabEditWorkflow->addTab('constants', dot('edit.tab.constants', $workflowInfo), dot('edit.constants', $workflowInfo)); 
  $tabEditWorkflow->addTab('inputs', dot('edit.tab.inputs', $workflowInfo), dot('edit.inputs', $workflowInfo)); 
  $tabEditWorkflow->addTab('actions', dot('edit.tab.actions', $workflowInfo), dot('edit.actions', $workflowInfo)); 
  $tabEditWorkflow->setDefaultTab(lavnn('tab') || 'questions');
  $workflowInfo['tabcontrol'] = $tabEditWorkflow->getHTML();
  $runtime->saveMoment('  tab control rendered');
  
} else {
  $page->add('title',  $workflowInfo['pagetitle'] = $runtime->doTemplate($module, 'edit.title.new');
  $workflowInfo['tabcontrol'] = $runtime->doTemplate($module, 'edit.info', $workflowInfo);
}
$page->add('main', $runtime->doTemplate($module, 'edit', $workflowInfo);



  
?>
