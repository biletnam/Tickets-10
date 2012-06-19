<?php

use ctlTab;

$contractData = array();
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $contractData = $runtime->s2r($module, 'GetContractData', array('contract_no' => $id)); #print Dumper($contractData);
  $page->add('title',  $contractData['pagetitle'] =  dot('title.view', $contractData);
  # Look up contract obligations and gifts
  $obligations = $runtime->s2a($module, 'ListContractObligations', array('contract_no' => $id));
#  print Dumper($obligations) . spreview($module, 'ListContractObligations', array('contract_no' => $id));
  if (count($obligations) > 0) {
    $contractData['obligations'] = $obligations; 
  } else {
    $contractData['copyobligations'] = $runtime->txt->do_template($module, 'view.obligations.copy', $contractData); 
  } 
  if ($contractData['merlin_id'] == '') {
    $contractData['merlin_upload_options'] = arr2ref($runtime->getDictArr('main', 'yesno', $contractData['merlin_upload_ready']));
    $contractData['mark_merlin_import_ready'] = $runtime->txt->do_template($module, 'view.details.merlinready', $contractData);
  }

  $fucomments = $runtime->s2a($module, 'ListFollowUpComments', array('contract_no' => $id));
  $contractData['fucomments'] = $fucomments;
  
  $gifts = $runtime->s2a($module, 'ListContractGifts', array('contract_no' => $id)); 
#  print Dumper($gifts) . spreview($module, 'ListContractGifts', array('contract_no' => $id));
  $contractData['gifts'] = $gifts;
  # Create a client view tab control
  $page['js'] = $runtime->txt->do_template('main', 'tabcontrol.js');
  $page['css'] = $runtime->txt->do_template('main', 'tabcontrol.css');
  $tabContractView = new ctlTab($r, "tcContractView");
  $tabContractView->addTab('details', dot('view.details.tabheader'), dot('view.details', $contractData)); 
  $tabContractView->addTab('obligations', dot('view.obligations.tabheader'), dot('view.obligations', $contractData)); 
  $tabContractView->addTab('gifts', dot('view.gifts.tabheader'), dot('view.gifts', $contractData)); 
  $tabContractView->addTab('attachments', dot('view.attachments.tabheader', $contractData), dot('view.attachments.wait', $contractData));
  $tabContractView->addTab('fucomments', dot('view.fucomments.tabheader'), dot('view.fucomments', $contractData)); 

  $tabContractView->setDefaultTab(lavnn('tab') || 'details');
  $contractData['tabcontrol'] = $tabContractView->getHTML();
  $runtime->saveMoment('  tab control rendered');

  # Render the whole page
  $page->add('main', $runtime->txt->do_template($module, 'view', $contractData);
}




?>
