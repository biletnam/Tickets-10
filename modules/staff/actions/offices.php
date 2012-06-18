<?php

$pageParams = array();
$offices = $acc->list_user_resources('editoffice', $r['userID']);
  
$offs = (count($offices) > 0) ? Arrays::join_column(',', 'source_id', $offices) : '';
$offs = '*' if $acc->is_superadmin();
@offices = $runtime->s2a($module, 'OfficeStaffStatistics', array('staff_offices' => $offs, 'staff_offices_noasterisk' => ($offs == '*' || $offs == '' ? '0' : $offs)));
$pageParams['offices'] = $offices;

$page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.offices');
$page->add('main', $runtime->doTemplate($module, 'offices.list', $pageParams);



?>
