<?php
$reference = lavnn('reference');
$id = lavnn('id');
if ($id <> '' && $reference <> '') {
  $runtime->srun($module, 'SetVoucherReference', $_REQUEST);
}
print $reference;
?>
