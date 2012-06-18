<?php

$id = lavnn('id');
if ($id > 0) {
  $reportInfo = $runtime->s2r($module, 'GetReportData', $_REQUEST); 

  use ctlTab;
  $tabReport = new ctlTab($r, "tcTicketView");
  $allowsortingoptions = $runtime->getDictArr('main', 'yesno', $reportInfo['allow_sorting']);
  $reportInfo['allowsortingoptions'] = $allowsortingoptions;
  $tabReport->addTab('data', dot('editreport.data.tabheader'), dot('editreport.data', $reportInfo)); 
  $reportInfo['params'] = arr2ref(render_report_parameters($id));
  $tabReport->addTab('params', dot('editreport.params.tabheader', $reportInfo), dot('editreport.params', $reportInfo)); 
  $tabReport->addTab('runaccess', dot('editreport.runaccess.tabheader', $articleInfo), dot('editreport.runaccess', $reportInfo));
  $tabReport->setDefaultTab(lavnn('tab') || 'data'); 
  $reportInfo['tabcontrol'] = $tabReport->getHTML();
  $runtime->saveMoment('  tab control rendered');
    
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page['js'] .= dotmod('main', 'linkpeople.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  $page->add('css',  dotmod('main', 'linkpeople.css');
  $page->add('title',  $reportInfo['pagetitle'] = $runtime->doTemplate($module, 'title.editreport', $reportInfo);
  $page->add('main', $runtime->doTemplate($module, 'editreport', $reportInfo);
} else {
  $pageParams = array();
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.newreport');
  $page->add('main', $runtime->doTemplate($module, 'newreport', $pageParams);
}







function render_report_parameters {
  ($id)
  
  $output = array();
  foreach $p (s2a($module, 'ListReportParameters', array('id' => $id))) {
    $mandatoryoptions = $runtime->getDictArr('main', 'yesno', $p['mandatory']); 
    $p['mandatoryoptions'] = $mandatoryoptions; 
    $p['rendered'] = $runtime->doTemplate($module, 'renderparameter', $$p);
    push @output, $p;
  }
  
  return $output;
}
?>
