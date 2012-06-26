<?php
use objBooking;
$objB = new objBooking($r);
$contractInfo = $objB->get_contract_info($_REQUEST);
print $runtime->txt->do_template($module, 'contracttooltip', $contractInfo);
?>
