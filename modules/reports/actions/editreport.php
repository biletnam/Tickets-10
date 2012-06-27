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
    
  $page['js'] .= $r->txt->do_template('main', 'tabcontrol.js');
  $page['js'] .= $r->txt->do_template('main', 'linkpeople.js');
  $page->add('css',  $r->txt->do_template('main', 'tabcontrol.css');
  $page->add('css',  $r->txt->do_template('main', 'linkpeople.css');
  $page->add('title',  $reportInfo['pagetitle'] = $r->txt->do_template($module, 'title.editreport', $reportInfo);
  $page->add('main', $r->txt->do_template($module, 'editreport', $reportInfo);
} else {
  $pageParams = array();
  $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.newreport');
  $page->add('main', $r->txt->do_template($module, 'newreport', $pageParams);
}







function render_report_parameters {
  ($id)
  
  $output = array();
  foreach $p (s2a($module, 'ListReportParameters', array('id' => $id))) {
    $mandatoryoptions = $runtime->getDictArr('main', 'yesno', $p['mandatory']); 
    $p['mandatoryoptions'] = $mandatoryoptions; 
    $p['rendered'] = $r->txt->do_template($module, 'renderparameter', $p);
    push @output, $p;
  }
  
  return $output;
}
?>
