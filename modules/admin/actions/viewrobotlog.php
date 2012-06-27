<?php

$pageParams  = array();

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %pageParams = $runtime->s2r($module, 'GetRobotDetails', $_REQUEST); 
  $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'viewrobotlog.title', $pageParams);
  $page->add('main', $r->txt->do_template($module, 'viewrobotlog', $pageParams);
} else {
  $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'viewrobotlog.none.title');
  $page->add('main', $r->txt->do_template($module, 'viewrobotlog.none');
}


  
?>
