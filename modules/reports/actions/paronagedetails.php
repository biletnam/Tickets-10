<?php
$paronageDetails = $runtime->s2r($module, 'GetParonageDetails', $_REQUEST);

$popupParams = (
  'title' => "Paronage commission details",
  'content' => dot('paronagedetails', $paronageDetails),
);

print dotmod('main', 'popup', $popupParams);
?>
