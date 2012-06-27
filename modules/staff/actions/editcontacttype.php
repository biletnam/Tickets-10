<?php

$typeInfo = array();
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %typeInfo = $runtime->s2r($module, 'GetContactTypeInfo', $_REQUEST);
  $page->add('title',  $typeInfo['pagetitle'] = $r->txt->do_template($module, 'title.settings.contacttypes.edit');
} else {
  %typeInfo = array();
  $page->add('title',  $typeInfo['pagetitle'] = $r->txt->do_template($module, 'title.settings.contacttypes.new');
}
$ispublicoptions = $runtime->getDictArr('main', 'yesno', $typeInfo['is_public']);
$typeInfo['ispublicoptions'] = $ispublicoptions;
$ismultipleoptions = $runtime->getDictArr('main', 'yesno', $typeInfo['is_multiple']);
$typeInfo['ismultipleoptions'] = $ismultipleoptions;
$runtime->saveMoment('  prepared yesno options');

$page->add('main', $r->txt->do_template($module, 'settings.contacttypes.edit', $typeInfo);



?>
