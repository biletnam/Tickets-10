<?php

use objTicketing;
$objT = new objTicketing($r);

$html = '';
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  # Get ticket information and its explanation
  $ticketInfo = $runtime->s2r($module, 'GetTicketInfo', array('id' => $id));
  $ticketInfo['explained'] = $runtime->txt->do_template($module, 'ticket.explain', $ticketInfo);
  # Get ticket history explained
  $activities = $objT->explain_full_history($id);
  $ticketInfo['actorinfo'] = $objT->render_actor($id);
  if (count($activities) > 5) {
    $ticketInfo['history.hidden'] = $runtime->txt->do_template($module, 'tickettooltip.history.hidden', array('cnt' => count($activities) - 5));
    @activities = @activities[0..4];
  }
  if (count($activities) > 0) {
    $ticketInfo['activities'] = $activities;
    $ticketInfo['history'] = $runtime->txt->do_template($module, 'viewticket.activities', $ticketInfo);
  } 
  $html = $runtime->txt->do_template($module, 'tickettooltip.ticketinfo', $ticketInfo); 
}
$html ||= $runtime->txt->do_template($module, 'tickettooltip.notfound', $ticketInfo);

$popupParams = array(
  'title' => "Ticket #$id",
  'content' => $html,
);

print $runtime->txt->do_template('main', 'popup', $popupParams);

?>
