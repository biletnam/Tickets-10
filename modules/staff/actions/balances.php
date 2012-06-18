<?php
$id = lavnn('office', $_REQUEST, 0);
if ($id <> 0) {
  $office = lavnn('office') || $r['userInfo']['lngWorkPlace'];
  if ($office <> 0) {
    %pageParams = $runtime->s2r($module, 'GetOfficeDetails', array('office' => $office));
    if (count($pageParams) > 0) {
      $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.balances', $pageParams);
      # Get year and month from request, or set current by default
      use Calendar;
      $today = Calendar::getTodayArr();
      $Y = lavnn('Y') || $today['Y'];
      $M = lavnn('M') || $today['M'];
      $employees = $runtime->s2a($module, 'GetEmployeeBalances', array('office' => $id, 'Y' => $Y)); 
      $pageParams['employees'] = $employees;
      $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.balances', $pageParams);
      $page->add('main', $runtime->txt->do_template($module, 'balances', $pageParams);
    }  
  }
}



?>
