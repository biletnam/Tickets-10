<?php

$pageParams  = array();

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %pageParams = $runtime->s2r($module, 'GetProfilerRecordDetails', $_REQUEST);
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'viewprofilertrace.title', $pageParams);
  $page->add('main', $runtime->txt->do_template($module, 'viewprofilertrace', $pageParams);
} else {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'viewprofilertrace.none.title');
  $page->add('main', $runtime->txt->do_template($module, 'viewprofilertrace.none');
}


  
?>
