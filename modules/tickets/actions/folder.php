<?php

$id = lavnn('id');
if ($id <> '') {
  $folderInfo = $runtime->s2r($module, 'GetFolderInfo', $_REQUEST);
  if (count($folderInfo) > 0) {
    $page->add('title',  $folderInfo['pagetitle'] = $r->txt->do_template($module, 'title.folder', $folderInfo);
    $folderInfo['tickets'] = arr2ref(s2a($module, 'ListFolderTickets', $_REQUEST));
    $page->add('main', $r->txt->do_template($module, 'folder', $folderInfo);
  } else {
    $page->add('title',  $folderInfo['pagetitle'] = $r->txt->do_template($module, 'title.folder.notfound');
    $page->add('main', $r->txt->do_template($module, 'folder.notfound', $projectInfo);
  }
} else {
  $page->add('title',  $folderInfo['pagetitle'] = $r->txt->do_template($module, 'title.folder.none');
  $page->add('main', $r->txt->do_template($module, 'folder.none');
}


?>
