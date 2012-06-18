<?php

$source = lavnn('src');
$controlname = lavnn('controlname');
if ($source <> '' && $controlname <> '') {
  $pageParams = ('src' => $source, 'controlname' => $controlname);
  $links = $runtime->s2a($module, 'ListPeopleLinks', $_REQUEST);
  if (count($links) > 0) {
    $links_bylinktype = Arrays::slice_array($links, 'link_type');
    @links = array();
    foreach $link (@{$links_bylinktype['1']}) {
      $link['offices'] = explainOffices($link['offices']) || dot('linkpeople.offices.all');
      $link['departments'] = explainDepartments($link['departments']) || dot('linkpeople.departments.all');
      $link['controlname'] = $controlname;
      push @links, dot('linkpeople.listitem.office', $$link);
    }
    foreach $link (@{$links_bylinktype['2']}) {
      $link['offices'] = explainOffices($link['offices']) || dot('linkpeople.offices.all');
      $link['departments'] = explainDepartments($link['departments']) || dot('linkpeople.departments.all');
      $link['controlname'] = $controlname;
      push @links, dot('linkpeople.listitem.department', $$link);
    }
    foreach $link (@{$links_bylinktype['3']}) {
      $link['people'] = explainPeople($link['people']) || dot('linkpeople.people.all');
      $link['controlname'] = $controlname;
      push @links, dot('linkpeople.listitem.person', $$link);
    }
    print dot('linkpeople.list', array('controlname' => $controlname, 'links' => join('', @links)));
  } else {
    print dot('linkpeople.list', array('controlname' => $controlname, 'links' => dot('linkpeople.none')));
  }
}

function explainOffices {
  ($ids)

  if ($ids <> '') {
    $offices = $runtime->s2a($module, 'GetOfficesByIds', array('ids' => $ids));  
    if (count($offices) > 0) {
      return Arrays::join_column(', ', 'name', $offices);
    }
  }
  return '';
}

function explainDepartments {
  ($ids)

  if ($ids <> '') {
    $departments = $runtime->s2a($module, 'GetDepartmentsByIds', array('ids' => $ids));  
    if (count($departments) > 0) {
      return Arrays::join_column(', ', 'name', $departments);
    }
  }
  return '';
}

function explainPeople {
  ($ids)

  if ($ids <> '') {
    $people = $runtime->s2a($module, 'GetPeopleByIds', array('ids' => $ids));  
    if (count($people) > 0) {
      return Arrays::join_column(', ', 'name', $people);
    }
  }
  return '';
}


1;
 
?>
