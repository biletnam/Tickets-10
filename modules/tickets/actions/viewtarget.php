<?php

$target = lavnn('target', $_REQUEST, '');

if ($target <> '') {
  $tickets = $runtime->s2a($module, 'ListTargetTickets', array('target' => $target));
  $pageParams = ('target' => $target, 'sessionID' => $sessionID, 'centralurl' => $_CONFIG['CENTRAL_SCRIPT_URL']);
  $pageParams{$module} = $tickets;
  $explanation = $objT->explain_target($target);
  $pageParams['explanationtitle'] = $explanation['title'];
  $pageParams['explanationtext'] = $explanation['text'];
  $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.viewtarget', $pageParams);
  $page->add('main', $runtime->doTemplate($module, 'viewtarget', $pageParams);
}





?>
