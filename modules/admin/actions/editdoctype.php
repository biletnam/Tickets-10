<?php

$doctypeInfo  = array();
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %doctypeInfo = $runtime->s2r($module, 'GetDocTypeInfo', $_REQUEST);
  $page->add('title',  $doctypeInfo['pagetitle'] = $runtime->txt->do_template($module, 'editdoctype.title.edit', $doctypeInfo);

  # Prepare tab control with editing controls
  use ctlTab;
  $tabEditDocType = new ctlTab($r, "tcEditDocType");
  $tabEditDocType->addTab('details', dot('editdoctype.details.tabheader'), dot('editdoctype.details', $doctypeInfo)); 
  $tabEditDocType->addTab('viewers', dot('editdoctype.employees.tabheader'), dot('editdoctype.employees', $doctypeInfo)); 
  $doctypeInfo['tabcontrol'] = $tabEditDocType->getHTML();
  $runtime->saveMoment('  tab control rendered');
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  $page['js'] .= dotmod('main', 'linkpeople.js');
  $page->add('css',  dotmod('main', 'linkpeople.css');
} else {
  $doctypeInfo['title'] = $doctypeInfo['pagetitle'] = $runtime->txt->do_template($module, 'editdoctype.title.new');
  $doctypeInfo['tabcontrol'] = $runtime->txt->do_template($module, 'editdoctype.details');
}
#$pageParams['doctypes'] = arr2ref(s2a($module, 'ListDocTypes'));
$page->add('main', $runtime->txt->do_template($module, 'editdoctype', $doctypeInfo);



  
?>
