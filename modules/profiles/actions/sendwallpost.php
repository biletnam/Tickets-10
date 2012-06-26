<?php

$entitytype = lavnn('entity_type', $_REQUEST, '');
$entityid = lavnn('entity_id', $_REQUEST, 0);
if ($entitytype == '' || $entityid == 0) {
  $_SESSION['error'] = 'Unknown wall post requested');
  go("?");
} else {
  $supported = qw(employee office department hotel generator);
  if (in_array($entitytype, $supported)) {
    $newid = $_REQUEST['post_id'] = $objWall->send_post($_REQUEST);
    $cnt_recipients = $objWall->distribute_post($_REQUEST) if $newid > 0;
    go("?p=$module/$entitytype&id=$entityid");    
  } else {
    $_SESSION['error'] = 'Unsupported wall post requested');
    go("?");
  }
}
?>
