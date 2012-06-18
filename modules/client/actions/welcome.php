<?php

if ($sess['user_id'] <> '') {
  $clientInfo = $runtime->s2r($module, 'GetClientInfo', array('id' => $sess['user_id']));
  if (count($clientInfo) > 0) {
    $pageParams = %clientInfo;
    $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.welcome', $pageParams);
    $page->add('main', $runtime->txt->do_template($module, 'welcome', $pageParams);
  }
}

print dotmod('main', 'client', $page);
 
?>
