<?php

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $pollInfo = $runtime->s2r($module, 'GetPollDetails', $_REQUEST); 
  $runtime->saveMoment('  fetched poll data from db');

  if (count($pollInfo) > 0) {
    if ($acc->check_resource("editpoll:$id", $r['userID'])) {
      $page->add('title',  $pollInfo['pagetitle'] = $runtime->txt->do_template($module, 'title.view', $pollInfo);
      $pollInfo['questions'] = $objP->render(('id' => $id, 'mode' => 'preview')) || 'None.';
      $page->add('main', $runtime->txt->do_template($module, 'preview', $pollInfo);
    } else {
      $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.noaccess', $pageParams);
      $page->add('main', $runtime->txt->do_template($module, 'noaccess', $pageParams);
    } 
  } else {
    $page->add('title',  $pageParams['pagetitle'] = $runtime->txt->do_template($module, 'title.notfound', $pageParams);
    $page->add('main', $runtime->txt->do_template($module, 'notfound');
  }

} else {
  $page->add('main',   dot('notfound');
}





?>
