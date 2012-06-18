<?php

$target = lavnn('target', $_REQUEST, '');

if ($target <> '') {
  $tickets = $runtime->s2a($module, 'ListTargetTickets', array('target' => $target));
  $pageParams = array('target' => $target, 'sessionID' => $sessionID, 'centralurl' => $_CONFIG['CENTRAL_SCRIPT_URL']);
  $pageParams{$module} = $tickets;
  $explanation = $objT->explain_target($target);
  $pageParams['explanationtitle'] = $explanation['title'];
  $pageParams['explanationtext'] = $explanation['text'];
  $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.viewtarget', $pageParams);
  $page->add('main', $runtime->txt->do_template($module, 'viewtarget', $pageParams);
}





?>
