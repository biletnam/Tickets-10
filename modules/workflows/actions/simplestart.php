<?php

$pageParams  = array();
  
$type = lavnn('type', $_REQUEST, '');
if ($type == 'employee_request_email_address') {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.start.employee_request_email_address');
  $pageParams['workflow'] = $objSW->start_employee_request_email_address(%_REQUEST); 
} elseif($type == 'employee_request_web_access') { 
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.start.employee_request_web_access');
  $pageParams['workflow'] = $objSW->start_employee_request_web_access(%_REQUEST);
} elseif($type == 'employee_request_merlin_access') { 
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.start.employee_request_merlin_access');
  $pageParams['workflow'] = $objSW->start_employee_request_merlin_access(%_REQUEST);
} elseif($type == 'employee_request_comm_query') { 
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.start.employee_request_comm_query');
  $pageParams['workflow'] = $objSW->start_employee_request_comm_query(%_REQUEST);
} else {
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.start.unknown');
  $pageParams['workflow'] = $runtime->txt->do_template($module, 'start.unknown');
}

$page->add('main', $runtime->txt->do_template($module, 'start', $pageParams);



 
?>
