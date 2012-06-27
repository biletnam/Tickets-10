<?php

$html = '';
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $nr = $runtime->s2a($module, 'ListUnsentNotificationRecipients', array('id' => $id));
  if (count($nr) > 20) {
    $html = $r->txt->do_template($module, 'notificationtooltip.many', $ticketInfo);
  } elseif(count($nr) > 0) {
    $html = $r->txt->do_template($module, 'notificationtooltip.recipients', array('recipients' => $nr)); 
  }
}
$html ||= $r->txt->do_template($module, 'notificationtooltip.none', $ticketInfo);
print $html;

?>
