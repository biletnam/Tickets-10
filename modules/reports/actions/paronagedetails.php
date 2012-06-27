<?php
$paronageDetails = $runtime->s2r($module, 'GetParonageDetails', $_REQUEST);

$popupParams = array(
  'title' => "Paronage commission details",
  'content' => dot('paronagedetails', $paronageDetails),
);

print $r->txt->do_template('main', 'popup', $popupParams);
?>
