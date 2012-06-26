<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $username = lavnn('username', $_REQUEST, '');
  $password = lavnn('password', $_REQUEST, '');
  # Save web login/password
  $clientData = $runtime->s2r($module, 'GetClientWebLogin', array('id' => $id));
  if (count($clientData) > 0) {
    # already exists, update if new username does not conflict with some other user
    $existingClientData = $runtime->s2r($module, 'GetClientWebLogin', array('username' => $username, 'id' => $id));
    if (count($existingClientData) > 0 && $existingClientData['client_id'] <> $id) {
      $_SESSION['error'] = 'Selected new Web access is already reserved for other client');
    } elseif (count($clientData) > 0) {
      $runtime->db->sqlrun($module, 'UpdateClientData', $_REQUEST);
      $_SESSION['flash'] = 'Web access info changed');
    }
  } else {
    # new client to get web-enabled
    if ($username <> '' && $password <> '') {
      $runtime->db->sqlrun($module, 'InsertClientData', $_REQUEST);
      $_SESSION['flash'] = 'Web access info changed');
    }
  }
  # Go back to editing page
  go("?p=$module/viewclient&id=$id&tab=webaccess");
} else {
  $_SESSION['error'] = 'Failed to change client data.');
  go("?p=$module/search");
}
?>
