<?php

$entitytype = lavnn('entity_type', $_REQUEST, '');
$entityid = lavnn('entity_id', $_REQUEST, 0);
if ($entitytype == '' || $entityid == 0) {
  set_cookie('error', 'Unknown wall post requested');
  go("?");
} else {
  $supported = qw(employee office department hotel generator);
  if (Arrays::in_array($entitytype, $supported)) {
    $newid = $_REQUEST['post_id'] = $objWall->send_post(%_REQUEST);
    $cnt_recipients = $objWall->distribute_post(%_REQUEST) if $newid > 0;
    go("?p=$module/$entitytype&id=$entityid");    
  } else {
    set_cookie('error', 'Unsupported wall post requested');
    go("?");
  }
}
?>
