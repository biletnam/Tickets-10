<?php

# Get list of staff according to criteria from %request
$office = lavnn('office', $_REQUEST, '');
$department = lavnn('department', $_REQUEST, '');
$pageParams = ('office' => $office, 'department' => $department, 'url' => lavnn('url'));
$_REQUEST['alloffices'] = $r['userInfo']['staff_offices'];
$_REQUEST['officeids'] = ($r['userInfo']['staff_offices'] == '*' || $r['userInfo']['staff_offices'] == '') ? '0' : $r['userInfo']['staff_offices'];
# define the sorting order from REQUEST's oldsort and newsort parameters
$sort = decode_sort_params(lavnn('oldsort'), lavnn('newsort'));
$_REQUEST['sort'] = $pageParams['oldsort'] = $sort;
$pageParams['fired'] = ($_REQUEST['fired'] == '1' ? 'fired' : '');
$staff = $runtime->s2a($module, 'ListStaff', $_REQUEST); #print Dumper($staff);

if (count($staff) > 0) { 
  $pageParams['oldsort'] = $sort;
  $pageParams['staff'] = $staff;
  
  if ($office <> '' && $acc->can_edit_staff($office)) {  
    # For multiple selection operation, we need some more data
    @officecalendars = $runtime->s2a($module, 'ListOfficeCalendars', array('office' => $office));
    @officecalendaroptions = genOptions($officecalendars, 'id', 'name');
    $pageParams['officecalendaroptions'] = $officecalendaroptions;
    @offices = $runtime->s2a($module, 'ListOffices');
    @officeoptions = genOptions($offices, 'lngId', 'strName');
    $pageParams['offices'] = $officeoptions;
    #$pageParams['newemployeeform'] = $runtime->doTemplate($module, 'newemployeeform', $pageParams) if $_REQUEST['fired'] <> '1'; # TODO
    print dot('searchresults.editoffice.list', $pageParams);
  } elseif ($department <> '' && $acc->can_edit_department($department)) {
    @departments = $runtime->s2a($module, 'ListDepartments');
    @departmentoptions = genOptions($departments, 'team_id', 'team_name');
    $pageParams['departments'] = $departmentoptions;
    print dot('searchresults.editdepartment.list', $pageParams);
  } else {
    $hroffices = $acc->list_access('editoffice', $r['userID']);
    if ($acc->is_superadmin() || count($hroffices) > 0) {
      print 'edit'.dot('searchresults.edit.list', $pageParams);
    } else {
      print 'view'.dot('searchresults.view.list', $pageParams);
    }
  }
} else {

  print dot('searchresults.none');  
}

function decode_sort_params {
  ($oldsort, $newsort)
  
  if ($newsort == 'firstname') {
    $sort = 'p.strFirstName';
  } elseif ($newsort == 'office') {
    $sort = 'o.strName';
  } elseif ($newsort == 'nick') {
    $sort = 'p.strNick';
  } else {
    $sort = 'p.strLastName';
  }
  ($oldsortfield, $oldsortdirection) = split(" ", $oldsort, 2);
  if ($oldsortfield == $sort) {
    $sort .= (' '.($oldsortdirection == 'ASC' ? 'DESC' : 'ASC'));
  } else {
    $sort .= ' ASC';
  }
  
  return $sort;
}
?>
