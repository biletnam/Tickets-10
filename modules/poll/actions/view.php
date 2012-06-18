<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $pollInfo = $runtime->s2r($module, 'GetPollDetails', $_REQUEST); 
  $runtime->saveMoment('  fetched poll data from db');

  if (count($pollInfo) > 0) {
    if ($pollInfo['is_template'] == 1) {
      # We restrict showing the poll if it is a template. Only copy of template can be viewed
      $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.noaccess', $pageParams);
      $page->add('main', $runtime->doTemplate($module, 'noaccess', $pageParams);
      
      
#      $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.viewtemplate', $pageParams);
#      $pollInfo['questions'] = $objP->render(('id' => $id, 'mode' => 'viewtemplate')) || 'None.';
#      $page->add('main', $runtime->doTemplate($module, 'viewtemplate', $pollInfo);
      
      
    } elseif ($acc->check_resource("viewpoll:$id", $r['userID'])) {
      # We restrict showing the poll only to the people who are specified as viewers
      $page->add('title',  $pollInfo['pagetitle'] = $runtime->doTemplate($module, 'title.view', $pollInfo);
      if ($pollInfo['can_change'] == 1) {
        $pollInfo['questions'] = $objP->render(('id' => $id, 'mode' => 'view')) || 'None.';
        $page->add('main', $runtime->doTemplate($module, 'view', $pollInfo);
      } else {
        $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.noaccess', $pageParams);
        $page->add('main', $runtime->doTemplate($module, 'noaccess', $pageParams);
      }
    } else {
      $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.noaccess', $pageParams);
      $page->add('main', $runtime->doTemplate($module, 'noaccess', $pageParams);
    } 
  } else {
    $page->add('title',  $pageParams['pagetitle'] = $runtime->doTemplate($module, 'title.notfound', $pageParams);
    $page->add('main', $runtime->doTemplate($module, 'notfound');
  }

} else {
  $page->add('main',   dot('notfound');
}





?>
