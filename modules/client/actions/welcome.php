<?php

if ($sess['user_id'] <> '') {
  $clientInfo = $runtime->s2r($module, 'GetClientInfo', array('id' => $sess['user_id']));
  if (count($clientInfo) > 0) {
    $pageParams = %clientInfo;
    $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.welcome', $pageParams);
    $page->add('main', $r->txt->do_template($module, 'welcome', $pageParams);
  }
}

print $r->txt->do_template('main', 'client', $page);
 
?>
