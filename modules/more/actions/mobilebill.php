<?php
use Calendar;
$pageParams = array();
$page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.mobilebill');
$parsedToday = Calendar::parseDate(Calendar::getToday());
$month = $parsedToday['month'];
$months = $runtime->getSortedDictArr('main', 'month', $month);
$pageParams['monthoptions'] = $months;
$year = $pageParams['year'] = $parsedToday['year'];
$page->add('main',  $r->txt->do_template($module, 'mobilebill', $pageParams);



?>
