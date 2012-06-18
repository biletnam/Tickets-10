<?php

$office = lavnn('office', $_REQUEST, 0);
if ($office <> 0) {
  $officeInfo = $runtime->s2r($module, 'GetOfficeDetails', $_REQUEST);
  $page->add('title',  $officeInfo['pagetitle'] = $runtime->doTemplate($module, 'title.office', $officeInfo);
  use ctlTab;
  $tabOffice = new ctlTab($r, 'ctOffice');
  if ($acc->can_edit_staff($office)) {
    $officeInfo['editform'] = $runtime->doTemplate($module, 'office.editform', $officeInfo);
    $calendars = $runtime->s2a($module, 'ListOfficeCalendars', $_REQUEST);
    $officeInfo['calendars'] = $calendars;
    $tabOffice->addTab('staff', dot('office.tab.staff'), dot('office.wait.staff', $officeInfo));
    $tabOffice->addTab('firedstaff', dot('office.tab.firedstaff'), dot('office.wait.firedstaff', $officeInfo));
    $tabOffice->addTab('contacts', dot('office.tab.contacts'), dot('office.wait.contacts', $officeInfo));
    $tabOffice->addTab('details', dot('office.tab.details'), dot('office.editform', $officeInfo));
#    $tabOffice->addTab('vacation', dot('office.tab.vacation'), dot('office.wait.vacation', $officeInfo));
    $tabOffice->addTab('calendars', dot('office.tab.calendars'), dot('office.calendars', $officeInfo));
    $tabOffice->addTab('attachments', dot('office.tab.attachments', $personInfo), dot('office.wait.attachments', $officeInfo));
    $tabOffice->addTab('viewers', dot('office.tab.viewers'), dot('office.wait.viewers', $officeInfo));
    $tabOffice->addTab('editors', dot('office.tab.editors'), dot('office.wait.editors', $officeInfo));
    $tabOffice->setDefaultTab(lavnn('tab') || 'staff');
    $page['js'] .= dotmod('main', 'linkpeople.js');
    $page->add('css',  dotmod('main', 'linkpeople.css');
    $page->add('css',  $runtime->doTemplate($module, 'calendar.css');
  } else {
    $tabOffice->addTab('details', dot('office.tab.details'), dot('office.details', $officeInfo));
    $tabOffice->addTab('contacts', dot('office.tab.contacts'), dot('office.wait.contacts', $officeInfo));
    $tabOffice->setDefaultTab(lavnn('tab') || 'contacts');
  }
  $page['js'] .= dotmod('main', 'tabcontrol.js');
  $page->add('css',  dotmod('main', 'tabcontrol.css');
  $page['js'] .= $runtime->doTemplate($module, 'search.sort.js');
  $officeInfo['tabcontrol'] = $tabOffice->getHTML();
  $officeInfo['hrlinks'] = $runtime->doTemplate($module, 'office.hrlinks', $officeInfo) if $acc->can_edit_staff($office);
  $page->add('main', $runtime->doTemplate($module, 'office', $officeInfo);
} else {
  $page->add('main', $runtime->doTemplate($module, 'office.notfound', $officeInfo);
}


      
?>
