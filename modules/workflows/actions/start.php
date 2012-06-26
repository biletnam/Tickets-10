<?php

$pageParams  = array();
  
$type = lavnn('type', $_REQUEST, '');
if ($type == 'employee.request_email_address') {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.start.employee.request_email_address');
  $_REQUEST['poll'] = 1;
  $pageParams['workflow'] = $objW->render_startpage($_REQUEST); 
} elseif($type == 'employee.request_web_access') { 
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.start.employee.request_web_access');
  $pageParams['workflow'] = $objW->render_startpage($_REQUEST); 
} else {
  $pageParams['worflow'] = $objW->render_startpage($_REQUEST);
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.start.unknown');
}

$page->add('main', $runtime->txt->do_template($module, 'start', $pageParams);



 
?>
