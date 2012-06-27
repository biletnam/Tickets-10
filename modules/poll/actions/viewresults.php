<?php

$user_type = $_REQUEST['user_type'] = lavnn('user_type') || 'U';
$user_id = $_REQUEST['user_id'] = lavnn('user_id') || $r['userID'];

$id = lavnn('id', $_REQUEST, '');
if ($id <> '') {
  $pollInfo = $runtime->s2r($module, 'GetPollDetails', $_REQUEST); 
  $runtime->saveMoment('  fetched poll data from db');

  if (count($pollInfo) > 0) {
    $can_view = $acc->check_resource("viewpoll:$id", $r['userID']);
    $can_edit = $acc->check_resource("editpoll:$id", $r['userID']);
    if ($can_view || $can_edit) {
      $page->add('title',  $pollInfo['pagetitle'] = $r->txt->do_template($module, 'title.viewresults', $pollInfo);
      $pollInfo['questions'] = $objP->render(('id' => $id, 'mode' => 'viewresults', 'user_type' => $user_type, 'user_id' => $user_id)) || 'None.';
      $page->add('main', $r->txt->do_template($module, 'viewresults', $pollInfo);
    } else {
      $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.noaccess', $pageParams);
      $page->add('main', $r->txt->do_template($module, 'noaccess', $pageParams);
    } 
  } else {
    $page->add('title',  $pageParams['pagetitle'] = $r->txt->do_template($module, 'title.notfound', $pageParams);
    $page->add('main', $r->txt->do_template($module, 'notfound');
  }

} else {
  $page->add('main',   dot('notfound');
}





?>
