<?php

$contractInfo = $runtime->s2r($module, 'GetContractData', $_REQUEST);
print dot('contracttooltip', $contractInfo);
?>
