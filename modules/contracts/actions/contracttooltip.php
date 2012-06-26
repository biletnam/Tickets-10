<?php

$contractInfo = $runtime->s2r($module, 'GetContractData', $_REQUEST);
print $runtime->txt->do_template($module, 'contracttooltip', $contractInfo);
?>
