<?php
$contract_no = lavnn('contract_no', $_REQUEST, '');
if ($contract_no <> '') {
  srun($module, 'CopyObligations', $_REQUEST);
  go("?p=$module/view&id=$contract_no&tab=obligations");  
} else {
  go("?p=$module/search");
}
?>
