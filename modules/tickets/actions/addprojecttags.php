<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $newtags = $runtime->trim(lavnn('newtags'), $_REQUEST, '');
  if ($newtags <> '') {
    $tags = split(',', $newtags);
    $success = 0;
    foreach $fulltag (@tags) {
      ($prefix, $tag) = split(':', $fulltag);
      $newid = sid($module, 'AddProjectTag', array('project' => $id, 'fulltag' => $fulltag, 'prefix' => $prefix, 'tag' => $tag));
      $success += ($newid > 0);
    }
    set_cookie('flash', "Added $success new tags to project") if $success > 0;
  }
  go("?p=tickets/project&id=$id&tab=tags");
} else {
  go('?p=tickets/myprojects');
}

?>