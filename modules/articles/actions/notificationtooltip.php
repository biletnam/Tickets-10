<?php

$html = '';
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $nr = $runtime->s2a($module, 'ListUnsentNotificationRecipients', array('id' => $id));
  if (count($nr) > 20) {
    $html = $runtime->doTemplate($module, 'notificationtooltip.many', $ticketInfo);
  } elseif(count($nr) > 0) {
    $html = $runtime->doTemplate($module, 'notificationtooltip.recipients', array('recipients' => $nr)); 
  }
}
$html ||= $runtime->doTemplate($module, 'notificationtooltip.none', $ticketInfo);
print $html;

?>
