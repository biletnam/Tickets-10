<?php

$pageParams  = array();

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  %pageParams = $runtime->s2r($module, 'GetRobotDetails', $_REQUEST); 
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'viewrobotlog.title', $pageParams);
  $page->add('main', $runtime->doTemplate($module, 'viewrobotlog', $pageParams);
} else {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'viewrobotlog.none.title');
  $page->add('main', $runtime->doTemplate($module, 'viewrobotlog.none');
}


  
?>
