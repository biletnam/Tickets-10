<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id <> 0) {
  $departmentInfo = $runtime->s2r($module, 'GetDepartmentDetails', $_REQUEST);
  $page->add('title',  $departmentInfo['pagetitle'] = $runtime->doTemplate($module, 'title.department', $departmentInfo);
  if ($acc->can_edit_department($id)) {
    $departmentInfo['editform'] = $runtime->doTemplate($module, 'department.editform', $departmentInfo);
    $departmentInfo['subteams'] = arr2ref(s2a($module, 'ListSubteams', $_REQUEST));
    use ctlTab;
    $tabDepartment = new ctlTab($r, 'ctDepartment');
    $tabDepartment->addTab('staff', dot('department.tab.staff'), dot('department.staff', $departmentInfo));
    $tabDepartment->addTab('firedstaff', dot('department.tab.firedstaff'), dot('department.firedstaff', $departmentInfo));
    $tabDepartment->addTab('details', dot('department.tab.details'), dot('department.editform', $departmentInfo));
    $tabDepartment->addTab('subteams', dot('department.tab.subteams'), dot('department.subteams', $departmentInfo));
    $tabDepartment->addTab('viewers', dot('department.tab.viewers'), dot('department.viewers', $departmentInfo));
    $tabDepartment->addTab('editors', dot('department.tab.editors'), dot('department.editors', $departmentInfo));
    $tabDepartment->setDefaultTab(lavnn('tab') || 'staff');
    $departmentInfo['tabcontrol'] = $tabDepartment->getHTML();
    $page['js'] .= dotmod('main', 'tabcontrol.js');
    $page['js'] .= dotmod('main', 'linkpeople.js');
    $page->add('css',  dotmod('main', 'tabcontrol.css');
    $page->add('css',  dotmod('main', 'linkpeople.css');
  } else {
    $departmentInfo['tabcontrol'] = $runtime->doTemplate($module, 'department.details', $departmentInfo) . dot('department.staff', $departmentInfo);
  }
  $page['js'] .= $runtime->doTemplate($module, 'search.sort.js');
  $page->add('main', $runtime->doTemplate($module, 'department', $departmentInfo);
} else {
  $page->add('main', $runtime->doTemplate($module, 'department.notfound', $departmentInfo);
}



?>
