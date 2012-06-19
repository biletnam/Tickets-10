<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id <> 0) {
  $departmentInfo = $runtime->s2r($module, 'GetDepartmentDetails', $_REQUEST);
  $page->add('title',  $departmentInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.department', $departmentInfo);
  if ($acc->can_edit_department($id)) {
    $departmentInfo['editform'] = $runtime->txt->do_template($module, 'department.editform', $departmentInfo);
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
    $page['js'] .= $runtime->txt->do_template('main', 'tabcontrol.js');
    $page['js'] .= $runtime->txt->do_template('main', 'linkpeople.js');
    $page->add('css',  $runtime->txt->do_template('main', 'tabcontrol.css');
    $page->add('css',  $runtime->txt->do_template('main', 'linkpeople.css');
  } else {
    $departmentInfo['tabcontrol'] = $runtime->txt->do_template($module, 'department.details', $departmentInfo) . dot('department.staff', $departmentInfo);
  }
  $page['js'] .= $runtime->txt->do_template($module, 'search.sort.js');
  $page->add('main', $runtime->txt->do_template($module, 'department', $departmentInfo);
} else {
  $page->add('main', $runtime->txt->do_template($module, 'department.notfound', $departmentInfo);
}



?>
