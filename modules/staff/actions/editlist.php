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
    $page['js'] .= dotmod('main', 'tabcontrol.js');
    $page->add('css',  dotmod('main', 'tabcontrol.css');
    $page['js'] .= dotmod('main', 'linkpeople.js');
    $page->add('css',  dotmod('main', 'linkpeople.css');
  } else {
    $pageParams['tabcontrol'] = $runtime->doTemplate($module, 'editlist.notfound');
  }
} else {
  $pageParams['tabcontrol'] = $runtime->doTemplate($module, 'editlist.details');
}
$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.editlist');
$page->add('main', $runtime->doTemplate($module, 'editlist', $pageParams);




?>
