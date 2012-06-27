<?php

$contractInfo = $runtime->s2r($module, 'GetContractData', $_REQUEST);
print $r->txt->do_template($module, 'contracttooltip', $contractInfo);
?>
