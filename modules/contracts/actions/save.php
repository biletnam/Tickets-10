<?php

$contract_number = lavnn('contract_no', $_REQUEST, 0);
if ($contract_number > 0) {
  $contractInfo = $runtime->s2r($module, 'GetContractData', $_REQUEST);
  if (count($contractInfo) > 0) {
    $result = $runtime->db->sqlrun($module, 'UpdateContract', $_REQUEST);
    if ($result > 0) {
      set_cookie("flash", "Success!");
    } else {
      set_cookie("error", "Could not save the changes");
    }
    go("?p=contracts/view&id=$contract_number");
  }
}
go('?p=contracts/search');
?>
