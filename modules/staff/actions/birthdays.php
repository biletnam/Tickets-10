<?php

use Calendar;

$pageParams = array();

$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.birthdays');

$parsedToday = Calendar::parseDate(Calendar::getToday()); 
$day = $_REQUEST['day'] || $parsedToday['day'];
$pageParams['day'] = $day; 
$month = $_REQUEST['month'] || $parsedToday['month'];
$pageParams['months'] = arr2ref($runtime->getDictArr('main', 'month', $month)); 

$birthdays = $runtime->s2a($module, 'ListBirthdays', array('day' => $day, 'month' => $month));
$oldoffice = ''; $rows = array();
$rowtemplate = $runtime->gettmod($module, 'birthdays.rowtemplate');
$sectiontemplate = $runtime->gettmod($module, 'birthdays.sectiontemplate');
foreach $_row (@birthdays) {
  $bd = $_row};
  $newoffice = $bd['OfficeName'];
  if ($newoffice <> $oldoffice) {
    push @rows, $r->txt->do_text($sectiontemplate, $bd);  
    $oldoffice = $newoffice;
  }
  push @rows, $r->txt->do_text($rowtemplate, $bd);
}
$pageParams['birthdays'] = join('', $rows);

$page->add('main',  $r->txt->do_template($module, 'birthdays', $pageParams);



?>
