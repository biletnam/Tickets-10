<?php

$id = lavnn('obligation', $_REQUEST, 0);

$ids = array();
while (($request_key, $request_value) = each %_REQUEST) {
  my($prefix, $suffix) = split('_', $request_key);
  if ($prefix == 'id' && $suffix <> '') {
    push @ids, $suffix;
  }
}

if ($id > 0) {
  $obligationInfo = $runtime->s2r($module, 'GetObligationData', array('id' => $id));
  if (count($obligationInfo) > 0) {
    if (count($ids) > 0) {
      $runtime->db->sqlrun($module, 'DeleteContractObligations', array('ids' => join(',', @ids)));
    } else {
      $runtime->set_cookie('error', 'Select some contracts first');
    }
    go("?p=$module/obligationusages&id=$id");
  } else {
    $runtime->set_cookie('error', 'No obligation chosen');
    go("?p=$module/list")
  } 
} else {
  $runtime->set_cookie('error', 'No obligation chosen');
  go("?p=$module/list")
}

?>
