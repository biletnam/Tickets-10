<?php

$ticket = lavnn('ticket');
$target_type = lavnn('target_type');
$target_id = lavnn('target_id');
if ($ticket > 0) {
  if ($target_type <> '' && $target_id <> '') {
    # Do some checks that objTicketing won't do
    if ($target_type == 'contract') {
      $contractInfo = $runtime->s2r($module, 'GetContractInfo', array('contract_no' => $target_id));
      $target_type = $target_id = '' if count($contractInfo) == 0; 
    }
    $result = $objT->register_target($ticket, "$target_type:$target_id") if $target_type <> '' && $target_id <> '';
    if ($result > 0) {
      $_SESSION['flash'] = 'New target linked to the ticket');
    } else {
      set_cookie('error', 'Could not link a new target');
    }
  } else {
    set_cookie('error', 'Could not link a new target');
  }
  go("?p=$module/viewticket&id=$ticket");
} else {
  go('');
}
?>
