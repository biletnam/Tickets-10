<?php
$pageParams = array();

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) { 
  $listInfo = $runtime->s2r($module, 'GetEmpListInfo', $_REQUEST); 
  if (count($listInfo) > 0) {
    use ctlTab;
    $tabEmpList = new ctlTab($r, 'ctOffice');
    $tabEmpList->addTab('details', dot('editlist.tab.details'), dot('editlist.details', $listInfo));
    $tabEmpList->addTab('members', dot('editlist.tab.members'), dot('editlist.wait.members', $listInfo));
    $tabEmpList->setDefaultTab(lavnn('tab') || 'members');
    $pageParams['tabcontrol'] = $tabEmpList->getHTML();
    $page['js'] .= $r->txt->do_template('main', 'tabcontrol.js');
    $page->add('css',  $r->txt->do_template('main', 'tabcontrol.css');
    $page['js'] .= $r->txt->do_template('main', 'linkpeople.js');
    $page->add('css',  $r->txt->do_template('main', 'linkpeople.css');
  } else {
    $pageParams['tabcontrol'] = $r->txt->do_template($module, 'editlist.notfound');
  }
} else {
  $pageParams['tabcontrol'] = $r->txt->do_template($module, 'editlist.details');
}
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.editlist');
$page->add('main', $r->txt->do_template($module, 'editlist', $pageParams);




?>
