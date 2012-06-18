<?php

use objEmployee;
$objEmp = new objEmployee($r);



$id = lavnn('id', $_REQUEST, '');
$personInfo = array();
$additionalInfo = array();

if ($id <> '') {
  %personInfo = $runtime->s2r($module, 'GetEmployeeDetails', $_REQUEST);
  $runtime->saveMoment('  person info fetched from db');
  if (count($personInfo) > 0) {
    %additionalInfo = $runtime->s2r($module, 'GetUserData', $_REQUEST); 
    Arrays::copy_fields($personInfo, $additionalInfo, qw(line_manager LineManagerName deputy_staff DeputyStaffName));
    Arrays::copy_fields($personInfo, $additionalInfo, qw(can_manage_clients can_manage_generators can_manage_campaigns));
    Arrays::copy_fields($personInfo, $additionalInfo, qw(can_manage_bookings can_manage_tradeins can_manage_vouchers));
    use objStaffManagement;
    $objSM = new objStaffManagement($r);
    if ($acc->can_edit_staff($personInfo['lngWorkPlace'])) {
      $runtime->saveMoment('  user edit access checked for this person, continuing...');
      #print Dumper($personInfo);
      $isFired = array($personInfo['dateFired'] <> '');
      if (!$isFired) {
        $personInfo['firetypes'] = arr2ref($runtime->getDictArr($module, 'firetype'));
        $personInfo['fireform'] = $runtime->txt->do_template($module, 'employee.edit.special.fireform', $personInfo);
      }

      # Get list of offices 
      $offices = $runtime->s2a($module, 'ListOffices');
      $officeoptions = genOptions($offices, 'lngId', 'strName', $personInfo['lngWorkPlace']);
      $personInfo['offices'] = $officeoptions;
      $runtime->saveMoment('  fetched list of offices from db');
  
      # Get list of departments 
      $departments = $runtime->s2a($module, 'ListDepartments');
      $departmentoptions = genOptions($departments, 'team_id', 'team_name', $personInfo['team_id']);
      $personInfo['departments'] = $departmentoptions;
      $runtime->saveMoment('  fetched list of departmens from db');
      
      # Get list of nationalities
      $nationalities = $runtime->s2a($module, 'ListNationalities');
      $nationalityoptions = genOptions($nationalities, 'nationality_id', 'nationality_name', $personInfo['lngNationality']);
      $personInfo['nationalities'] = $nationalityoptions;
      $runtime->saveMoment('  prepared nationalities options');
      
      # Get list of genders
      $genders = $runtime->getDictArr('main', 'gender', $personInfo['lngSex']);
      $personInfo['sexoptions'] = $genders;
      $runtime->saveMoment('  prepared gender options');
      
      # Get avatar pictire, if any
      if ($personInfo['avatarfile'] <> '') {
        $imageInfo = $runtime->s2r('main', 'GetUploadedFileInfo', array('id' => $personInfo['avatarfile']));
        $personInfo['avatarpicture'] = $runtime->txt->do_template($module, 'avatarimage', $imageInfo);
      }

      # Get list of office calendars
      $calendars = $runtime->s2a($module, 'ListEmployeeOfficeCalendars', array('id' => $personInfo['lngId']));
      $personInfo['calendars'] = $calendars;
           
      # Get list of user comments
      $comments = $objSM->get_employee_comments(%personInfo);
      $personInfo['comments'] = $comments;

      # Get list of user attachments
      $attachments = $objSM->get_employee_attachments(%personInfo); #print Dumper($attachments);
      $personInfo['attachments'] = $attachments;
      $expecteddoctypes = $objSM->list_expected_doctypes('employee' => $id); 
      $doctypeids = join(',', @expecteddoctypes);
      $doctypes = $runtime->s2a('admin', 'ListDocTypes', array('ids' => $doctypeids));
      $existingdoctypes = Arrays::cut_column_unique($attachments, 'doctype');
      $missingdoctypes = Arrays::cut_column_unique($doctypes, 'id', 'except' => $existingdoctypes); 
      $missing = $runtime->s2a('admin', 'ListDocTypes', array('ids' => join(',', @missingdoctypes)));
      $personInfo['missingdocs'] = $runtime->txt->do_template($module, 'employee.missing.doctypes', array('missing' => join_column(',', 'name', $missing))) if count($missing) > 0; 

      $personInfo['doctypeoptions'] = arr2ref(genOptions($doctypes, 'id', 'name'));

      # Get list of users for whom current user is either line manager or deputy staff
      $line_manager_for = $objSM->line_manager_for($id);
      $personInfo['line_manager_for'] = $line_manager_for;
      $personInfo['related_staff'] = count($line_manager_for);
      $deputy_staff_for = $objSM->deputy_staff_for($id);
      $personInfo['deputy_staff_for'] = $deputy_staff_for;
  
      # Run problem report
      $problemReport = $objEmp->get_problem_report(('id' => $id));
      $problemReport .= $personInfo['missingdocs'];
      
      # Show user password for superadmin
      if ($acc->is_superadmin()) {
        $personInfo['show_password'] = $runtime->txt->do_template($module, 'employee.edit.showpassword', $personInfo);
      }
      
      use ctlTab;
      $tabEmployee = new ctlTab($r, 'ctEmployee');
      $tabEmployee->addTab('contact', dot('employee.tab.contact'), dot('employee.wait.contact', array('id' => $id)));
      $tabEmployee->addTab('personal', dot('employee.tab.personal'), dot('employee.edit.personal', $personInfo));
      $tabEmployee->addTab('special', dot('employee.tab.special'), dot('employee.edit.special', $personInfo)) if ($id <> '' && !$isFired);
      $tabEmployee->addTab('teams', dot('employee.tab.teams'), dot('employee.wait.teams', $personInfo)) if ($id <> '' && !$isFired);
      $tabEmployee->addTab('calendars', dot('employee.tab.calendars'), dot('employee.edit.calendars', $personInfo));
      $tabEmployee->addTab('comments', dot('employee.tab.comments'), dot('employee.edit.comments', $personInfo));
      $tabEmployee->addTab('attachments', dot('employee.tab.attachments', $personInfo), dot('employee.wait.attachments', $personInfo));
      $tabEmployee->addTab('fired', dot('employee.tab.fired'), dot('employee.edit.fired', $personInfo)) if $isFired;
      $tabEmployee->addTab('problemreport', dot('employee.tab.problemreport'), $problemReport) if $problemReport <> '';
      $tabEmployee->setDefaultTab(lavnn('tab') || 'personal');
      $personInfo['tabcontrol'] = $tabEmployee->getHTML();
      
      $page['js'] = dotmod('main', 'tabcontrol.js');
      $page['css'] = dotmod('main', 'tabcontrol.css');    
      $page['js'] .= $runtime->txt->do_template($module, 'employeeteams.js');

      $page->add('title',  $personInfo['pagetitle'] = $runtime->txt->do_template($module, 'person.title.edit', $personInfo);
      $page->add('main', $runtime->txt->do_template($module, 'employee.edit', $personInfo);
      $runtime->saveMoment('  main part of the page rendered');

    } elseif ($acc->is_line_manager($id)) {

      # Get list of user comments
      $comments = $objSM->get_employee_comments(%personInfo);
      $personInfo['comments'] = $comments;
      # Get list of user attachments
      $attachments = $objSM->get_employee_attachments(%personInfo);
      $personInfo['attachments'] = $attachments;

      # Get list of user contacts which are public and given
      $contacts = $objSM->get_employee_contact_info($id, '', 1, 1);
      $personInfo['contacts'] = $contacts;
      $personInfo['calendarlink'] = $runtime->txt->do_template($module, 'employee.view.calendarlink', $personInfo);
      $personInfo['calendarbalance'] = $runtime->txt->do_template($module, 'employee.view.calendarbalance', $personInfo);

      use ctlTab;
      $tabEmployee = new ctlTab($r, 'ctEmployee');
      $tabEmployee->addTab('view', dot('employee.tab.contact', $personInfo), dot('employee.view.manager', $personInfo));
      $tabEmployee->addTab('comments', dot('employee.tab.comments'), dot('employee.edit.comments', $personInfo));
      $tabEmployee->addTab('attachments', dot('employee.tab.attachments', $personInfo), dot('employee.edit.attachments', $personInfo));
      $tabEmployee->setDefaultTab(lavnn('tab') || 'view');
      $personInfo['tabcontrol'] = $tabEmployee->getHTML();
      
      $page['js'] = dotmod('main', 'tabcontrol.js');
      $page['css'] = dotmod('main', 'tabcontrol.css');    
      $page->add('title',  $personInfo['pagetitle'] = $runtime->txt->do_template($module, 'person.title.view', $personInfo);
      $page->add('main', $runtime->txt->do_template($module, 'employee.edit', $personInfo);
      $runtime->saveMoment('  main part of the page rendered');

    } else {

      # Get list of user contacts which are public and given
      $contacts = $objSM->get_employee_contact_info($id, '', 1, 1);
      $personInfo['contacts'] = $contacts;
      $page->add('title',  $personInfo['pagetitle'] = $runtime->txt->do_template($module, 'person.title.view', $personInfo);
      $page->add('main', $runtime->txt->do_template($module, 'employee.view', $personInfo);
      
    }
  } else {
    $page->add('title',  $personInfo['pagetitle'] = $runtime->txt->do_template($module, 'person.title.notfound');
    $page->add('main', $runtime->txt->do_template($module, 'employee.notfound', $personInfo);
  }
}



?>
