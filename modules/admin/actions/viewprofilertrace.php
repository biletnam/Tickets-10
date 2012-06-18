<?php

$pageParams  = array();

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %pageParams = $runtime->s2r($module, 'GetProfilerRecordDetails', $_REQUEST);
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'viewprofilertrace.title', $pageParams);
  $page->add('main', $runtime->doTemplate($module, 'viewprofilertrace', $pageParams);
} else {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'viewprofilertrace.none.title');
  $page->add('main', $runtime->doTemplate($module, 'viewprofilertrace.none');
}


  
?>
