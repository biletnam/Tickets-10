<?php

$alloffices = $runtime->s2a($module, 'ListOffices');
$typeInfo = array();
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %typeInfo = $runtime->s2r($module, 'GetCalendarDayTypeInfo', $_REQUEST); 
  $daytypeoffices = $runtime->s2a($module, 'ListCalendarDayTypeOffices', array('id' => $id));
  $ids = Arrays::join_column(',', 'lngId', $daytypeoffices);
  $typeInfo['offices'] = arr2ref(genCheckboxes($alloffices, 'office', 'lngId', 'strName', $ids));
  $typeInfo['absenceoptions'] = arr2ref($runtime->getDictArr('main', 'yesno', $typeInfo['is_absence']));
  $typeInfo['paidoptions'] = arr2ref($runtime->getDictArr('main', 'yesno', $typeInfo['is_paid']));
  $page->add('title',  $typeInfo['pagetitle'] = $runtime->doTemplate($module, 'title.settings.daytypes.edit');
} else {
  $typeInfo['offices'] = arr2ref(genCheckboxes($alloffices, 'office', 'lngId', 'strName', $ids));
  $typeInfo['absenceoptions'] = arr2ref($runtime->getDictArr('main', 'yesno'));
  $typeInfo['paidoptions'] = arr2ref($runtime->getDictArr('main', 'yesno'));
  $page->add('title',  $typeInfo['pagetitle'] = $runtime->doTemplate($module, 'title.settings.daytypes.new');
}

$page->add('main', $runtime->doTemplate($module, 'settings.daytypes.edit', $typeInfo);



?>
